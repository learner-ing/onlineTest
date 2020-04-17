import axios from "axios";
import config from "../../config";

export default axios.create({
  baseURL: config.baseURL,
  withCredentials: true,
  timeout: 5000
});
