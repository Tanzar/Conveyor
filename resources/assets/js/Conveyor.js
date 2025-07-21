import Echo from "laravel-echo";
import { formConveyorKey } from "./formConveyorKey";

export class Conveyor {
    #channel;
    #listening = false;

    constructor(key, params, handle) {
        this.#channel = formConveyorKey(key, params)

        if (typeof handle === 'function') {
            Echo.private(this.#channel)
                .listen('.conveyor.updated', (data) => {
                    handle(data.data);
                });
            this.#listening = true;

        } else {

            console.log('Conveyor error: handle must be function')
        }
    }

    /**
     * Call to end stream
     */
    destroyed() {
        if (this.#listening) {
            Echo.leave(this.#channel);
        }
    }
}
