const EMAIL_REG = /^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;

export const validateEmail = function (email: string) {
  return EMAIL_REG.test(email);
};
