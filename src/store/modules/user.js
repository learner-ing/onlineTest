import request from "../../utils/request";

const types = {
  SET_USER: "SET_USER",
  UPDATE_USER: "UPDATE_USER"
};

const state = {
  user: false
};

const getters = {};

const actions = {
  login({ commit }, data) {
    return new Promise((resolve, reject) => {
      request
        .post("/login", data)
        .then(res => {
          if (res.data.error === false) {
            commit(types.SET_USER, res.data.data);
            resolve(res);
          } else {
            reject(res);
          }
        })
        .catch(err => {
          reject(err.response);
        });
    });
  },
  logout({ commit }) {
    return new Promise((resolve, reject) => {
      request
        .post("/logout")
        .then(res => {
          if (res.data.error === false) {
            commit(types.SET_USER, false);
            resolve(res);
          } else {
            reject(res);
          }
        })
        .catch(err => {
          reject(err.response);
        });
    });
  },
  register({ commit, state }, data) {
    return new Promise((resolve, reject) => {
      request
        .post("/register", data)
        .then(res => {
          if (res.data.error === false) {
            resolve(res);
          } else {
            reject(res);
          }
        })
        .catch(err => {
          reject(err.response);
        });
    });
  },
  update({ commit, state }, data) {
    return new Promise((resolve, reject) => {
      request
        .put("/users", data)
        .then(res => {
          if (res.data.error === false) {
            commit(types.UPDATE_USER, data);
            resolve(res);
          } else {
            reject(res);
          }
        })
        .catch(err => {
          reject(err.response);
        });
    });
  },
  changePassword({ commit }, data) {
    return new Promise((resolve, reject) => {
      request
        .put("/password", data)
        .then(res => {
          if (res.data.error === false) {
            resolve(res);
          } else {
            reject(res);
          }
        })
        .catch(err => {
          reject(err.response);
        });
    });
  }
};

const mutations = {
  [types.SET_USER](state, user) {
    state.user = user;
  },
  [types.UPDATE_USER](state, user) {
    state.user.username = user.username;
    state.user.email = user.email;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
