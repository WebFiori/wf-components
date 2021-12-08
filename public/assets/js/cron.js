var app = new Vue({
    el:'#app',
    vuetify: new Vuetify(),
    data: {
        password:'',
        search:'',
        loading:false,
        jobs:[],
        expanded:[],
        jobs_table_headers:[
            {value:'name', text:'Job Name'},
            {value:'expression', text:'CRON Expression'},
            {value:'time.is_minute', text:'Is Minute'},
            {value:'time.is_hour', text:'Is Hour'},
            {value:'time.is_day_of_week', text:'Is Day of Week'},
            {value:'time.is_day_of_month', text:'Is Day of Month'},
            {value:'time.is_month', text:'Is Month'},
            {value:'actions', text:'Actions'},
        ],
        dialog:{
            show:false,
            message:''
        },
        output_dialog:{
            show:false,
            output:''
        }
    },
    computed:{
        login_btn_disabled:function() {
            var pass = this.password+'';
            return pass.trim().length === 0;
        }
    },
    mounted:function () {
        if (data.title === 'Scheduled CRON Tasks') {
            this.loadTasks();
        }
    },
    methods:{
        forceExec:function (job) {
            console.log(job);
            var vue = this;
            var params = {
                'job-name':job.name
            };
            for(var x = 0 ; x < job.args.length ; x++) {
                var argVal = job.args[x].value;
                if (argVal !== undefined && argVal !== null && argVal.length !== 0) {
                    params[job.args[x].name] = argVal;
                }
            }
            new AJAXRequest({
                method:'post',
                url:data.base+'/cron/apis/force-execution',
                params:params,
                beforeAjax:function() {
                    vue.loading = true;
                    job.executing = true;
                },
                afterAjax:function() {
                    vue.loading = false;
                    job.executing = false;
                    vue.output_dialog.output = this.response;
                },
                onSuccess:function() {
                    if (this.jsonResponse) {
                        vue.showDialog(this.jsonResponse.message);
                    } else {
                        vue.showDialog('Something went wrong. Try again.');
                    }
                },
                onServerErr:function() {
                    if (this.jsonResponse) {
                        if (this.jsonResponse.message) {
                            vue.showDialog(this.jsonResponse.message);
                        } else {
                            vue.showDialog(this.status+' - Server Error.');
                        }
                    } else {
                        vue.showDialog(this.status+' - Server Error.');
                    }
                },
                onClientErr:function() {
                    if (this.jsonResponse) {
                        if (this.jsonResponse.message) {
                            vue.showDialog(this.jsonResponse.message);
                        } else {
                            vue.showDialog(this.status+' - Client Error.');
                        }
                    } else {
                        vue.showDialog(this.status+' - Client Error.');
                    }
                },
                onDisconnected:function() {
                    vue.showDialog('Check your internet connection and try again.');
                }
            }).send();
        },
        loadTasks:function() {
            var vue = this;
            new AJAXRequest({
                method:'get',
                url:data.base+'/cron/apis/get-jobs',
                beforeAjax:function() {
                    vue.loading = true;
                },
                afterAjax:function() {
                    vue.loading = false;
                },
                onSuccess:function() {
                    if (this.jsonResponse) {
                        vue.jobs = this.jsonResponse.jobs;
                    } else {
                        vue.showDialog('Something went wrong. Try again.');
                    }
                },
                onServerErr:function() {
                    if (this.jsonResponse) {
                        if (this.jsonResponse.message) {
                            vue.showDialog(this.jsonResponse.message);
                        } else {
                            vue.showDialog(this.status+' - Server Error.');
                        }
                    } else {
                        vue.showDialog(this.status+' - Server Error.');
                    }
                },
                onClientErr:function() {
                    if (this.jsonResponse) {
                        if (this.jsonResponse.message) {
                            vue.showDialog(this.jsonResponse.message);
                        } else {
                            vue.showDialog(this.status+' - Client Error.');
                        }
                    } else {
                        vue.showDialog(this.status+' - Client Error.');
                    }
                },
                onDisconnected:function() {
                    vue.showDialog('Check your internet connection and try again.');
                }
            }).send();
        },
        dialogClosed:function() {
            this.dialog.show = false; 
        },
        showDialog(message) {
            this.dialog.message = message;
            this.dialog.show = true;
        },
        logout:function() {
            var vue = this;
            new AJAXRequest({
                method:'post',
                url:data.base+'/cron/apis/logout',
                params:{
                    password:vue.password
                },
                beforeAjax:function() {
                    vue.loading = true;
                },
                afterAjax:function() {
                    vue.loading = false;
                },
                onSuccess:function() {
                    if (this.jsonResponse) {
                        window.location.href = data.base+'/cron/login';
                    } else {
                        vue.showDialog('Something went wrong. Try again.');
                    }
                },
                onServerErr:function() {
                    if (this.jsonResponse) {
                        if (this.jsonResponse.message) {
                            vue.showDialog(this.jsonResponse.message);
                        } else {
                            vue.showDialog(this.status+' - Server Error.');
                        }
                    } else {
                        vue.showDialog(this.status+' - Server Error.');
                    }
                },
                onClientErr:function() {
                    if (this.jsonResponse) {
                        if (this.jsonResponse.message) {
                            vue.showDialog(this.jsonResponse.message);
                        } else {
                            vue.showDialog(this.status+' - Client Error.');
                        }
                    } else {
                        vue.showDialog(this.status+' - Client Error.');
                    }
                },
                onDisconnected:function() {
                    vue.showDialog('Check your internet connection and try again.');
                }
            }).send();
        },
        login:function() {
            var vue = this;
            new AJAXRequest({
                method:'post',
                url:data.base+'/cron/apis/login',
                params:{
                    password:vue.password
                },
                beforeAjax:function() {
                    vue.loading = true;
                },
                afterAjax:function() {
                    if (this.status !== 200) {
                        vue.loading = false;
                    }
                },
                onSuccess:function() {
                    if (this.jsonResponse) {
                        window.location.href = data.base+'/cron/jobs';
                    } else {
                        vue.showDialog('Something went wrong. Try again.');
                    }
                },
                onServerErr:function() {
                    if (this.jsonResponse) {
                        if (this.jsonResponse.message) {
                            vue.showDialog(this.jsonResponse.message);
                        } else {
                            vue.showDialog(this.status+' - Server Error.');
                        }
                    } else {
                        vue.showDialog(this.status+' - Server Error.');
                    }
                },
                onClientErr:function() {
                    if (this.jsonResponse) {
                        if (this.jsonResponse.message) {
                            vue.showDialog(this.jsonResponse.message);
                        } else {
                            vue.showDialog(this.status+' - Client Error.');
                        }
                    } else {
                        vue.showDialog(this.status+' - Client Error.');
                    }
                },
                onDisconnected:function() {
                    vue.showDialog('Check your internet connection and try again.');
                }
            }).send();
        }
    }
});