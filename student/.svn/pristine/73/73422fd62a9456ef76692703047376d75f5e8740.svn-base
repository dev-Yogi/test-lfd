
if (typeof home !== 'undefined') {
    var chart_logins = c3.generate({
        bindto: '#chart_logins',
        data: {
            x: 'x',
            columns: [
                ['x'].concat(report_logins.x),
                ['logins'].concat(report_logins.y),
            ],
            colors: {
                completions: '#467fcf'
            }
        },
        axis: {
            x: {
                label: 'Date',
                type: 'timeseries',
                tick: {
                    format: '%Y-%m-%d'
                }
            },
            y: {
                label: 'Number of Logins'
            }
        }
    });

    var chart_course_completions = c3.generate({
        bindto: '#chart_course_completions',
        data: {
            x: 'x',
            columns: [
                ['x'].concat(report_course_completions.x),
                ['completions'].concat(report_course_completions.y),
            ],
            colors: {
                completions: '#45aaf2'
            }
        },
        axis: {
            x: {
                label: 'Date',
                type: 'timeseries',
                tick: {
                    format: '%Y-%m-%d',
                    count: 10
                }
            },
            y: {
                label: 'Number of Completions'
            }
        }
    });

    var chart_lesson_completions = c3.generate({
        bindto: '#chart_lesson_completions',
        data: {
            x: 'x',
            columns: [
                ['x'].concat(report_lesson_completions.x),
                ['completions'].concat(report_lesson_completions.y),
            ],
            colors: {
                completions: '#6574cd'
            }
        },
        axis: {
            x: {
                label: 'Date',
                type: 'timeseries',
                tick: {
                    format: '%Y-%m-%d',
                    count: 10
                }
            },
            y: {
                label: 'Number of Completions'
            }
        }
    });
} else {
    var chart = c3.generate({
        data: {
            x: 'x',
            columns: [
                ['x'].concat(report.x),
                ['logins'].concat(report.y),
            ],
            colors: {
                completions: '#467fcf'
            }
        },
        axis: {
            x: {
                label: 'Date',
                type: 'timeseries',
                tick: {
                    format: '%Y-%m-%d'
                }
            },
            y: {
                label: 'Number of Logins'
            }
        }
    });
}


function redirectReport() {
    var report = $('#report-dropdown').val();
    window.location.replace(base_url + 'admin/report/' + report);
}