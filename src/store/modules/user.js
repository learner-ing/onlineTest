import request from "../../utils/request";

const types = {
  SET_USER: "SET_USER"
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
          reject(err);
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
          reject(err);
        });
    });
  }
};

const mutations = {
  [types.SET_USER](state, user) {
    state.user = user;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
