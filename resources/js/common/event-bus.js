export default {
    events: {},
    emit(eventName, payload) {
        if (this.events[eventName]) this.events[eventName].emit(payload);
    },
    on(eventName, subscriber) {
        if (typeof subscriber !== "function") return false;

        if (!this.events[eventName]) {
            this.events[eventName] = {
                listeners: [],
                emit(payload) {
                    this.listeners.forEach((listener) => listener(payload));
                },
            };
        }

        this.events[eventName].listeners.push(subscriber);

        return true;
    },

    off(eventName) {
        if (!this.events[eventName]) return false;

        delete this.events[eventName];
    },

    has(eventName) {
        return !!this.events[eventName];
    },
};
