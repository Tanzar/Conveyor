import { Conveyor } from "./Conveyor";

/**
 * setup for Echo object, use for default setup
 * @param {string} key 
 * @param {Object} params 
 * @param {Function} handle - formated conveyor data is passed as json to first parameter
 * @param {Function} onError - handle when gets error connecting, ignored if not function
 * @returns {Conveyor}
 */
export function conveyor(key, params, handle, onError) {
    return new Conveyor(key, params, handle, onError);
}

export const ConveyorVue = {
    install(app, options) {
        const c = (key, params, handle, onError) =>
            conveyor(key, params, handle, onError);

        if (parseInt(app.version) > 2) {
            app.provide('conveyor', c);
        } else {
            app.mixin({
                methods: {
                    conveyor: c,
                },
            });
        }
    },
};