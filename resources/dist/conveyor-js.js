import { useEcho as c } from "@laravel/echo-vue";
import i from "laravel-echo";
function s(r, o) {
  if (typeof r != "string")
    throw new Error("Conveyor error: key must be string");
  if (typeof o != "object")
    throw new Error("Conveyor error: params must be object");
  let e = "conveyor." + r + "-";
  return Object.keys(o).forEach((t) => {
    e += t + "=" + o[t] + ";";
  }), e;
}
class f {
  #o;
  #e = !1;
  constructor(o, e, t) {
    this.#o = s(o, e), typeof t == "function" ? (i.private(this.#o).listen(".conveyor.updated", (n) => {
      t(n.data);
    }), this.#e = !0) : console.log("Conveyor error: handle must be function");
  }
  /**
   * Call to end stream
   */
  destroyed() {
    this.#e && i.leave(this.#o);
  }
}
function v(r, o, e) {
  return new f(r, o, e);
}
function l(r, o, e) {
  let t = s(r, o);
  c(
    t,
    ".conveyor.updated",
    (n) => {
      e(n.data);
    }
  );
}
export {
  v as newConveyor,
  l as useConveyor
};
