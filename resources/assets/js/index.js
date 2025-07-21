import { useEcho } from "@laravel/echo-vue";
import { Conveyor } from "./Conveyor";
import { formConveyorKey } from "./formConveyorKey";

/**
 * setup for Echo object, use for default setup
 * @param {string} key 
 * @param {Object} params 
 * @param {Function} handle - formated conveyor data is passed as json to first parameter
 * @returns {Conveyor}
 */
export function newConveyor(key, params, handle) {
    return new Conveyor(key, params, handle);
}

/**
 * setup for vue, use if you setup echo for vue
 * @param {string} key 
 * @param {Object} params 
 * @param {Function} handle - formated conveyor data is passed as json to first parameter
 */
export function useConveyor(key, params, handle) {
    let conveyorKey = formConveyorKey(key, params);

    useEcho(
        conveyorKey,
        '.conveyor.updated',
        (event) => {
            handle(event.data);
        }
    );
}