export function formConveyorKey(key, params) {
    if (typeof key !== 'string') {
            throw new Error('Conveyor error: key must be string');
        }

        if (typeof params !== 'object') {
            throw new Error('Conveyor error: params must be object');
        }

        let result = 'conveyor.' + key + '-';

        Object.keys(params).forEach(paramKey => {
            result += paramKey + '=' + params[paramKey] + ';';
        });

        return result;
}