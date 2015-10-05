(function() {
    var init, setupShepherd;

    init = function() {
        return setupShepherd();
    };

    setupShepherd = function() {
        var shepherd;
        shepherd = new Shepherd.Tour({
            defaults: {
                classes: 'shepherd-element shepherd-open shepherd-theme-arrows',
                showCancelLink: true
            }
        });
        shepherd.addStep('welcome', {
            text: ['Welcome. Glad you are here! This is your main menu. "My Ads" shows your ordered banners.<br> "Timeline" shows all events connecter with you. Have questions ? <i class="fa fa-envelope-o"></i> <a href="https://adtw.ch/contact-us">Contact us now</a>'],
            attachTo: '.nav-settings right',
            classes: 'shepherd shepherd-open shepherd-theme-arrows shepherd-transparent-text',
            buttons: [
                {
                    text: 'Exit',
                    classes: 'shepherd-button-secondary',
                    action: shepherd.cancel
                }, {
                    text: 'Next',
                    action: shepherd.next,
                    classes: 'shepherd-button-example-primary'
                }
            ]
        });
        shepherd.addStep('including', {
            title: 'Including',
            text: 'Including Shepherd is easy! Just include shepherd.js, and a Shepherd theme file.',
            attachTo: '.nav-user right',
            buttons: [
                {
                    text: 'Back',
                    classes: 'shepherd-button-secondary',
                    action: shepherd.back
                }, {
                    text: 'Next',
                    action: shepherd.next
                }
            ]
        });
        shepherd.addStep('followup', {
            title: 'Learn more',
            text: 'Star Shepherd on Github so you remember it for your next project',
            attachTo: '.booking-table-first bottom',
            buttons: [
                {
                    text: 'Back',
                    classes: 'shepherd-button-secondary',
                    action: shepherd.back
                }, {
                    text: 'Done',
                    action: shepherd.next
                }
            ]
        });
        return shepherd.start();
    };

    $(init);

}).call(this);
