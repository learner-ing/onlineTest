import Vue from "vue";
import store from "../store";
import request from "../utils/request";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home
  },
  {
    path: "/user/:path",
    name: "User",
    component: () => import("../views/User.vue"),
    meta: {
      requireAuth: true
    }
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("../views/Login.vue"),
    meta: {
      requireGuest: true
    }
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("../views/Register.vue"),
    meta: {
      requireGuest: true
    }
  },
  {
    path: "/exams",
    name: "Exams",
    component: () => import("../views/Exams.vue"),
    meta: {
      requireAuth: true
    }
  }
];

const router = new VueRouter({
  routes
});

// 路由守卫
router.beforeEach((to, from, next) => {
  if (to.meta.requireAuth || to.meta.requireGuest) {
    if (store.state.user.user === false) {
      request
        .get("/users")
        .then(res => {
          if (res.data.error === false) {
            store.commit("user/SET_USER", res.data.data);
            if (to.meta.requireGuest) {
              next({ path: "/" });
            } else {
              next({ path: from.query.redirect });
            }
          } else {
            if (to.meta.requireGuest) {
              next();
            } else {
              next({
                path: "/login",
                query: { redirect: to.fullPath }
              });
            }
          }
        })
        .catch(err => {
          if (to.meta.requireGuest) {
            next();
          } else {
            next({
              path: "/login",
              query: { redirect: to.fullPath }
            });
          }
        });
    } else {
      if (to.meta.requireGuest) {
        next({ path: "/" });
      } else {
        next();
      }
    }
  } else {
    next();
  }
});

export default router;
