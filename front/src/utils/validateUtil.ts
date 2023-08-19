const EMAIL_REG = /^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;

const STARS_REG = /^https?:\/\/img.shields.io\/github/;

export const validateEmail = function (email: string) {
  return EMAIL_REG.test(email);
};

export const validateStars = function (url: string) {
  return STARS_REG.test(url);
};
