<template>
  <el-row class="exams-container">
    <ex-header></ex-header>
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
import Header from "../components/Header";
export default {
  name: "exams",
  components: {
    "ex-header": Header
  },
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
