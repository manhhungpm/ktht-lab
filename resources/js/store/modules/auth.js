import axios from "axios";
import Cookies from "js-cookie";
import * as types from "../mutation-types";
import { API_PROFILE, API_LOGOUT, API_REFRESH } from "~/constants/url";

// state
export const state = {
    user: null,
    token: Cookies.get("token")
};

// getters
export const getters = {
    user: state => (state.user !== null ? state.user.info : null),
    token: state => state.token,
    permissions: state => (state.user ? state.user.permissions : null),
    roles: state => (state.user ? state.user.roles : null),
    check: state => state.user != null
};

// mutations
export const mutations = {
    [types.SAVE_TOKEN](state, { token, remember }) {
        state.token = token;
        Cookies.set("token", token, { expires: remember ? 365 : null });
    },

    [types.FETCH_USER_SUCCESS](state, { user }) {
        state.user = user;
    },

    [types.FETCH_USER_FAILURE](state) {
        state.token = null;
        state.perms = [];
        Cookies.remove("token");
    },

    [types.LOGOUT](state) {
        state.user = null;
        state.token = null;

        Cookies.remove("token");
    },

    [types.UPDATE_USER](state, { user }) {
        state.user = user;
    }
};

// actions
export const actions = {
    saveToken({ commit }, payload) {
        commit(types.SAVE_TOKEN, payload);
    },

    async fetchUser({ commit, dispatch }) {
        try {
            const { data } = await axios.post(API_PROFILE);

            setInterval(() => {
                dispatch("refreshToken");
            }, 30 * 60 * 1000);

            commit(types.FETCH_USER_SUCCESS, { user: data.user });
        } catch (e) {
            commit(types.FETCH_USER_FAILURE);
        }
    },

    async refreshToken({ commit }) {
        try {
            const { data } = await axios.post(API_REFRESH);

            if (data.code == 0) {
                commit(types.SAVE_TOKEN, {
                    token: data.access_token,
                    remember: true
                });
            }
        } catch (e) {
            console.log(e);
        }
    },

    updateUser({ commit }, payload) {
        commit(types.UPDATE_USER, payload);
    },

    async logout({ commit }) {
        try {
            await axios.post(API_LOGOUT);
        } catch (e) {
            console.log(e);
        }

        commit(types.LOGOUT);
    }
};
