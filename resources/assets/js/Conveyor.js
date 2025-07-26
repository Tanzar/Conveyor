import axios from "axios";
import Echo from "laravel-echo";

export class Conveyor {
    #channel;
    #listening = false;

    constructor(key, params, handle, onError = () => {}) {

        if (typeof handle === 'function') {
            const query = new URLSearchParams(params).toString();
            const url = '/conveyor/join/' + key + '?' + query;

            axios.get(url)
                .then(response => {
                    this.#channel = response.data.channel;

                    handle(response.data.state);
                    
                    window.Echo.private(response.data.channel)
                        .listen('.conveyor.updated', (data) => {
                            handle(data.data);
                        });
                    this.#listening = true;
                })
                .catch(error => {
                    if (typeof onError !== 'function') {
                        onError(error);
                    }
                })
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
