export const USER_NAME_REGEX = /^[A-Za-z][A-Za-z0-9_]{0,}[A-Za-z0-9]$/;

export const PASSWORD_REGEX = /(?=.*[a-z])+(?=.*[A-Z])+(?=.*\d)+(?=.*[$~!#^()@$!%*?&])[A-Za-z\d$~!#^()@$!%*?&]{8,}/;
export const PHONE_REGEX = /^[0-9+][0-9]*[0-9]$/;
export const PARTNER_CODE = /^[a-zA-Z0-9]*$/;
export const IS_PRODUCT_TEXT = /^[\w0-9_&]*$/;
