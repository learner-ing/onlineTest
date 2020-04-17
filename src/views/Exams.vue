<template>
  <el-row class="exams-container">
    <header class="exams-header">
      <img src="../assets/smxy-logo.png" alt="SMXY-Logo" />
      <el-menu default-active="1" mode="horizontal">
        <el-menu-item index="1">处理中心</el-menu-item>
        <el-submenu index="2">
          <template slot="title">我的工作台</template>
          <el-menu-item index="2-1">选项1</el-menu-item>
          <el-menu-item index="2-2">选项2</el-menu-item>
          <el-menu-item index="2-3">选项3</el-menu-item>
          <el-submenu index="2-4">
            <template slot="title">选项4</template>
            <el-menu-item index="2-4-1">选项1</el-menu-item>
            <el-menu-item index="2-4-2">选项2</el-menu-item>
            <el-menu-item index="2-4-3">选项3</el-menu-item>
          </el-submenu>
        </el-submenu>
      </el-menu>
      <el-dropdown>
        <span class="el-dropdown-link">
          <i class="el-icon-user"></i>
          { username }
        </span>
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item>个人信息</el-dropdown-item>
          <el-dropdown-item>更新信息</el-dropdown-item>
          <el-dropdown-item divided>登出</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </header>
    <el-carousel trigger="click" height="50vh" :autoplay="false">
      <el-carousel-item
        v-for="item in carousels"
        :key="item.id"
        :style="{ backgroundImage: `url(${item.background})` }"
      >
        <h1>{{ item.title }}</h1>
      </el-carousel-item>
    </el-carousel>
    <main class="exams-main el-container">
      <el-row>
        <el-col :md="8" v-for="item in exams" :key="item.id">
          <el-card>
            <div slot="header">
              <span>{{ item.title }}</span>
              <el-button type="text">
                开始
              </el-button>
            </div>
            <div class="note">{{ item.notes }}</div>
            <div class="time">答题时间：{{ item.answer_time }} 分钟</div>
            <div class="time">
              开始时间：{{ new Date(item.start_time).toLocaleString() }}
            </div>
            <div class="time">
              结束时间：{{ new Date(item.end_time).toLocaleString() }}
            </div>
          </el-card>
        </el-col>
      </el-row>
    </main>
  </el-row>
</template>

<script>
export default {
  name: "exams",
  data() {
    return {
      carousels: [
        {
          title: "Title1",
          background: "https://lab.ixk.me/api/bing?day=0"
        },
        {
          title: "Title1",
          background: "https://lab.ixk.me/api/bing?day=1"
        },
        {
          title: "Title1",
          background: "https://lab.ixk.me/api/bing?day=2"
        },
        {
          title: "Title1",
          background: "https://lab.ixk.me/api/bing?day=3"
        }
      ],
      exams: [
        {
          id: 1,
          title: "Exam 1",
          notes: "Exam 1",
          question_type: [
            {
              type: "radio",
              count: 10
            }
          ],
          answer_time: 10,
          start_time: Date.now(),
          end_time: Date.now()
        },
        {
          id: 2,
          title: "Exam 2",
          notes: "Exam 2",
          question_type: [
            {
              type: "radio",
              count: 10
            }
          ],
          answer_time: 10,
          start_time: Date.now(),
          end_time: Date.now()
        }
      ]
    };
  }
};
</script>

<style lang="scss" scoped>
.exams-container {
  color: #f8f8f8;
}

.exams-header {
  height: 60px;
  display: flex;
  align-items: center;

  img {
    height: 80%;
    margin-right: 3rem;
  }

  .el-dropdown {
    flex: 1;
    text-align: right;

    .el-dropdown-link {
      cursor: pointer;
    }
  }
}

.el-carousel__item {
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #ffffff;

  &::after {
    position: absolute;
    content: "";
    background-color: rgba($color: #000000, $alpha: 0.2);
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: -1;
  }
}

.exams-main {
  .el-row {
    width: 100%;

    .el-col {
      padding: 2% 1%;

      .el-card__header div {
        display: flex;

        .el-button {
          padding: 3px 0;
          flex: 1;
          text-align: right;
        }
      }

      .time,
      .note {
        line-height: 1.5;
      }

      .time {
        color: #9b9b9b;
      }
    }
  }
}
</style>
