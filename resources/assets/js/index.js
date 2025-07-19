import { Conveyor } from "./Conveyor";

/**
 * setup new container
 * @param {string} key 
 * @param {Object} params 
 * @returns {Conveyor}
 */
export function conveyor(key, params) {
    return new Conveyor(key, params);
}

window.conveyor = conveyor;