import n from "laravel-echo";
class c {
  #o;
  constructor(o, e = {}) {
    if (typeof o != "string")
      throw new Error("Conveyor error: key must be string");
    if (typeof e != "object")
      throw new Error("Conveyor error: params must be object");
    this.#o = "conveyor." + o + "-", Object.keys(e).forEach((t) => {
      this.#o += t + "=" + e[t] + ";";
    });
  }
  /**
   * Add listener called when conveyor updates
   * @param {Function} handle function form managing event
   * @returns {Conveyor}
   */
  onUpdate(o) {
    return typeof o == "function" ? n.private(this.#o).listen("conveyor.updated", (e) => {
      o(e.data);
    }) : console.log("Conveyor error: handle must be function"), this;
  }
  /**
   * Call to end detecting stream
   */
  destroyed() {
    n.leave(this.#o);
  }
}
function i(r, o) {
  return new c(r, o);
}
window.conveyor = i;
export {
  i as conveyor
};
