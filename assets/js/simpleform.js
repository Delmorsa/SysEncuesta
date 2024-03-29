/*

Copyright (c) 2012 Rob Graham rob@rfgraham.net http://www.rfgraham.net

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

(function($) {

    $.fn.simpleform = function(options) {

        var self = this;

        // Start index counter
        var i = 0;

        // Parameters (options) relevant to the plugin
        // and its functionality.
        var params = {
            next: 'Siguiente',
            previous: 'Anterior',
            submit: 'Submit',
            transition: 'fade',
            speed: 500,
            validate: false,
            progressBar: true,
            showProgressText: true,
        };

        // Get all fieldsets of the current form
        var $target = self.find('fieldset');

        // Active fieldset height
        var targetHeight;

        // Height of active fieldset, progress bar
        // and controls
        var totalHeight;

        var $controls = {
            container: undefined,
            next: undefined,
            previous: undefined,
            submit: undefined
        };

        var controlsHeight;

        // Set progress bar height to 0 incase the
        // user requests the plugin not to use it, that way
        // it's height is not calculated
        var progressBarHeight = 0;

        // This determines how many sections there are
        var totalSections = $target.length;

        // This is our total percentage which we devide
        // by totalSections
        var fullProgress = 100;

        // Used for whether fieldset is valid after validation
        var isFormValid;

        //  If options are passed, override the default values
        if (options) {
            $.extend(params, options);
        }

        //  If progressBar is set true, then add the Progress Bar
        //  which returns the height used later to calculate totalHeight
        if (params.progressBar) {
            addProgressBar();
        }

        // Append the form controls to the button //<input type="submit" value="' + params.submit + '" id="submit-button" class="simple-form-button" /> \
        self.append('<div class="form-controls"> \
						<input type="button" style="margin-right:10px;" value="' + params.previous + '" id="previous-fieldset" class="simple-form-button" /> \
						<input type="button" value="' + params.next + '" id="next-fieldset" class="simple-form-button" /> \
						<div class="clear" /> \
					</div>');

        // After the form controls have been added, store their
        // values of this.form (self);
        $controls.container = self.find('.form-controls');
        $controls.previous = self.find('#previous-fieldset');
        $controls.next = self.find('#next-fieldset');
        $controls.submit = self.find('#submit-button');
        progressBarHeight = $progressBar.outerHeight(true);

        // If not set in CSS already, hide any of the fieldsets that
        // are not the first.
        $target.not(':first').hide();

        // Go Back click event
        $controls.previous.click(function(e) {

            // If the form is NOT animating, pass the new index
            // to go back 1 fieldset.
            if (!isAnimating()) {
                i--;
                changeTarget(i);
            }

        });

        // Go Forward click event
        $controls.next.click(function(e) {

            // Before moving forward, does the user have Validation
            // turned on?
            if (params.validate) {

                // Store the result of the form validation by calling
                // the validateForm function outside of this plugin,
                // manually created by the user which contains the validation.
                // The function needs to return true on the validation
                // for us to continue.
                isFormValid = validateForm(self);

                // If Validation was successful and the form isn't animating, continue.
                if (isFormValid && !isAnimating()) {
                    i++;
                    changeTarget(i);
                }
            }

            // Otherwise just continue the regular route.
            else if (!isAnimating()) {
                i++;
                changeTarget(i);
            }

        });

        $controls.submit.click(function(e) {

            // Before moving forward, does the user have validation
            // turned on?
            if (params.validate) {
                isFormValid = validateForm(self);
            }

            if (!isFormValid) e.preventDefault();

        });

        // Checks to see if the current form is being animating
        function isAnimating() {
            return self.is(':animated');
        }

        // Function controlling the changing of each fieldset including
        // the transition effect requested by the user
        function changeTarget(index) {

            // Get the total heights of each element that need to be
            // calculated. This will ensure that the animation of the
            // form's height is accurate to accommodate its elements.
            targetHeight = $target.eq(index).outerHeight(true);
            controlsHeight = $controls.container.outerHeight(true);
            totalHeight = targetHeight + controlsHeight + progressBarHeight;

            switch (params.transition) {
                case 'fade':
                    $target.fadeOut(params.speed);
                    $controls.container.css('visibility', 'hidden');
                    self.removeAttr('style');
                    self.animate({ height: totalHeight },
                        params.speed,
                        function() {
                            $target.eq(index).fadeIn(params.speed);
                            $controls.container.css('visibility', 'visible');
                            showControls(index);
                            self.removeAttr('style').css('min-height', totalHeight + 'px');
                        }
                    );
                    break;

                case 'slide':
                    $controls.container.css('visibility', 'hidden');
                    self.removeAttr('style');
                    self.animate({ height: totalHeight },
                        params.speed,
                        function() {
                            $target.hide();
                            $target.eq(index).show();
                            $controls.container.css('visibility', 'visible');
                            showControls(index);
                            self.removeAttr('style');
                            self.css('min-height', totalHeight + 'px');
                        }
                    );

                    break;

                default:
                    $target.hide();
                    $target.eq(index).show();
                    showControls(index);
            }

            // Activate the progress bar's new value and position
            progressBarSetIndex(index);
        }

        // Show the controls dependent on the forms position.
        function showControls(index) {

            // If our index is less than or equal to zero, we dont
            // need to display the Previous button.
            if (index <= 0) {
                $controls.previous.hide();
            } else {
                $controls.previous.show();
            }

            // If we're more than or equal to the total sections,
            // display the submit button and remove the Next button
            if (index >= totalSections - 1) {
                $controls.next.hide();
                self.find('#submit-button').show();
            } else {
                $controls.next.show();
                self.find('#submit-button').hide();
            }

        }

        // Progress bar is activated if true in our parameters. This
        // prepends the bar at the top of the form.
        function addProgressBar() {
            self.prepend('<div class="progress-bar"> \
							<span class="progress-text"></span> \
							<span class="progress-bg"></span> \
						</div>');

            $progressBar = self.find('.progress-bar');
        }

        // Our progress bar animation
        function progressBarSetIndex(index) {

            // If true in parameters, display the navigation information
            if (params.showProgressText) {
                self.find('.progress-text').text((index + 1) + "/" + totalSections);
            }

            self.find('.progress-bg').animate({
                    width: (fullProgress / totalSections) * (index + 1) + "%"
                },
                params.speed);
        }

        progressBarSetIndex(0);

    };

})(jQuery);