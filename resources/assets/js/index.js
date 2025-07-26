import { Conveyor } from "./Conveyor";

/**
 * setup for Echo object, use for default setup
 * @param {string} key 
 * @param {Object} params 
 * @param {Function} handle - formated conveyor data is passed as json to first parameter
 * @param {Function} onError - handle when gets error connecting, ignored if not function
 * @returns {Conveyor}
 */
export function newConveyor(key, params, handle, onError) {
    return new Conveyor(key, params, handle, onError);
}