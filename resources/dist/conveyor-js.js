import l from "axios";
import f from "laravel-echo";
class h {
  #o;
  #t = !1;
  constructor(i, e, o, c = () => {
  }) {
    if (typeof o == "function") {
      const r = new URLSearchParams(e).toString(), s = "/conveyor/join/" + i + "?" + r;
      l.get(s).then((n) => {
        this.#o = n.data.channel, o(n.data.state), window.Echo.private(n.data.channel).listen(".conveyor.updated", (a) => {
          o(a.data);
        }), this.#t = !0;
      }).catch((n) => {
        typeof c != "function" && c(n);
      });
    } else
      console.log("Conveyor error: handle must be function");
  }
  /**
   * Call to end stream
   */
  destroyed() {
    this.#t && f.leave(this.#o);
  }
}
function u(t, i, e, o) {
  return new h(t, i, e, o);
}
const d = {
  install(t, i) {
    const e = (o, c, r, s) => u(o, c, r, s);
    parseInt(t.version) > 2 ? t.provide("conveyor", e) : t.mixin({
      methods: {
        conveyor: e
      }
    });
  }
};
export {
  d as ConveyorVue,
  u as conveyor
};
