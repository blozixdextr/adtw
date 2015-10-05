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
            title: 'We glad to see you!',
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
            title: 'Your Profile',
            text: 'Fill information about your stream here. It`s help advertisers to find your stream in search.',
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
            title: 'Ordered banners',
            text: 'Here you can see all banners. Click to review its and accept or decline banners.',
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
