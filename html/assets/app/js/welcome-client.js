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
            text: ['Shepherd is a javascript library for guiding users through your app. It uses <a href="http://github.hubspot.com/tether/">Tether</a>, another open source library, to position all of its steps.', 'Tether makes sure your steps never end up off screen or cropped by an overflow. Try resizing your browser to see what we mean.'],
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