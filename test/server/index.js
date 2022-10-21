var rpc = require('jayson');

var server = rpc.server({
    concat: function concatFn(foo, bar, done) {
        if (arguments.length === 3) {
            done(null, foo + bar);
        } else {
            done(this.error(-32602));
        }
    },
    sum: function sumFn(foo, bar, done) {
        if (arguments.length === 3) {
            done(null, foo + bar);
        } else {
            done(this.error(-32602));
        }
    },
    notify: function notifyFn(foo, done) {
        done();
    },
    foo: function fooFn(done) {
        if (arguments.length === 1) {
            done(null, 'foo');
        } else {
            done(this.error(-32602));
        }
    }
});

console.log('listening on port 80');
server.http().listen(80);
