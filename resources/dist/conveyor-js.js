import f from "axios";
import h from "laravel-echo";
class l {
  #t;
  #o = !1;
  constructor(e, n, t, i = () => {
  }) {
    if (typeof t == "function") {
      const c = new URLSearchParams(n).toString(), r = "/conveyor/join/" + e + "?" + c;
      f.get(r).then((o) => {
        this.#t = o.data.channel, t(o.data.state), window.Echo.private(o.data.channel).listen(".conveyor.updated", (s) => {
          t(s.data);
        }), this.#o = !0;
      }).catch((o) => {
        typeof i != "function" && i(o);
      });
    } else
      console.log("Conveyor error: handle must be function");
  }
  /**
   * Call to end stream
   */
  destroyed() {
    this.#o && h.leave(this.#t);
  }
}
function d(a, e, n, t) {
  return new l(a, e, n, t);
}
export {
  d as newConveyor
};
