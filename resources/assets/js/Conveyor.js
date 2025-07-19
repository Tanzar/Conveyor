import Echo from "laravel-echo";

export class Conveyor {
    #channel;

    constructor(key, params = {}) {
        if (typeof key !== 'string') {
            throw new Error('Conveyor error: key must be string');
        }

        if (typeof params !== 'object') {
            throw new Error('Conveyor error: params must be object');
        }

        this.#channel = 'conveyor.' + key + '-';

        Object.keys(params).forEach(paramKey => {
            this.#channel += paramKey + '=' + params[paramKey] + ';';
        });
    }

    /**
     * Add listener called when conveyor updates
     * @param {Function} handle function form managing event
     * @returns {Conveyor}
     */
    onUpdate(handle) {
        if (typeof handle === 'function') {
            Echo.private(this.#channel)
                .listen('conveyor.updated', (data) => {
                    handle(data.data);
                });

        } else {

            console.log('Conveyor error: handle must be function')
        }
        return this;
    }

    /**
     * Call to end detecting stream
     */
    destroyed() {
        Echo.leave(this.#channel);
    }
}
