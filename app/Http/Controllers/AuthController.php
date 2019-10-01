<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use \phpCAS;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public $maxAttempts = 5; // change to the max attempt you want.
    public $decayMinutes = 5; // change to the minutes you want.
    public function username()
    {
        return 'name';
    }


    /**
     * AuthController constructor.
     * @param UserRepository $repo
     */
    public function __construct(UserRepository $repo)
    {
        $this->middleware('auth:api', ['except' => ['login', 'ssoLogin', 'ssoLogout']]);
        $this->repo = $repo;
    }

    /**
     * @OA\POST(
     *     path="/auth/login",
     *     tags={"auth"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="The user name for login",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         description="The password for login",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Header(
     *             header="X-Rate-Limit",
     *             description="calls per hour allowed by the user",
     *             @OA\Schema(
     *                 type="integer",
     *                 format="int32"
     *             )
     *         ),
     *         @OA\Header(
     *             header="X-Expires-After",
     *             description="date in UTC when token expires",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *              allOf = {
     *                  @OA\Schema(ref="#/components/schemas/ApiResponse"),
     *                  @OA\Schema(
     *                      type = "object",
     *                      @OA\Property(
     *                          property="token",
     *                          example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTU2MTA4MjUwNiwiZXhwIjoxNTYxMDg2MTA2LCJuYmYiOjE1NjEwODI1MDYsImp0aSI6IkVKTGR3NFp0bUk4MHhMd2siLCJzdWIiOjY5LCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.rl6byduMST86xDwinMZLpml2rb_kRv4nXmXx7cXhkgE",
     *                          type="string"
     *                      )
     *                  )
     *              }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid username/password supplied"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = request(['name', 'password']);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return response()->json(['code' => CODE_ERROR,'message' => 'Đăng nhập thất bại quá nhiều. Đợi '.$this->decayMinutes.' phút để thử lại'], 400);
        }

        if (!$token = auth()->attempt($credentials)) {
            $this->incrementLoginAttempts($request);
            return response()->json([
                'code' => CODE_ERROR,
                'message' =>  'Thông tin đăng nhập không đúng!'
            ], 401);
        }
//        event(new LoggedIn(auth()->user(), $request->ip()));
        if(Carbon::createFromFormat('Y-m-d H:i:s.u', auth()->user()->expired_at)->lt(Carbon::now())){
            return response()->json([
                'code' => CODE_ERROR,
                'message' =>  'Tài khoản hết hạn'
            ], 401);
        } else {
            return $this->respondWithToken($token);
        }
    }

    public function ssoLogin()
    {
        $this->initCas();
        phpCAS::forceAuthentication();
        if (phpCAS::getUser()) {
            $username = phpCAS::getUser();
            $user = User::where('name', $username)->first();
            if ($user) {
                $token = auth()->login($user);
                return response()->redirectTo('/sso-login?token=' . $token);
            } else {
                return response()->redirectTo('/not_found');
            }
        }
    }

    /**
     * @OA\POST(
     *     path="/auth/me",
     *     tags={"auth"},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Header(
     *             header="Authorization",
     *             description="Token to authen",
     *             @OA\Schema(
     *                 type="string"
     *             )
     *         ),
     *         @OA\JsonContent(
     *              allOf = {
     *                  @OA\Schema(ref="#/components/schemas/ApiResponse"),
     *                  @OA\Schema(
     *                      type = "object",
     *                      @OA\Property(
     *                          property="user",
     *                          type="object",
     *                          ref="#/components/schemas/User"
     *                      )
     *                  )
     *              }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid username/password supplied"
     *     )
     * )
     */
    public function me()
    {
        $user = auth()->user();

        return response()->json([
            'code' => 0,
            'user' =>
                [
                    'info' => $user,
                    'roles' => $user->roles()->select('id', 'name')->where('active', 1)->get(),
                    'permissions' => $user->permissions()->select('id', 'value')->where('active', 1)->get(),
                ],

            'message' => 'success'
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function ssoLogout()
    {
        $this->initCas();
        phpCAS::logout(['service' => 'http://127.0.0.1:8000/auth/sso-login']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        return response()->json([
            'code' => CODE_SUCCESS,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    protected function initCas()
    {
        $cas_host = env('CAS_HOST', '10.60.156.97:8225');
        $cas_context = env('CAS_CONTEXT', '/sso');
        $cas_port = 443;

        phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

        phpCAS::setNoCasServerValidation();
    }
}