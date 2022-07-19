humhub.module('legal', function(module, require, $) {
    var Widget = require('ui.widget').Widget;

    const config = function(option, defaultValue) {
        if (typeof defaultValue === 'undefined') {
            defaultValue = '';
        }

        return module.config.externalLink && module.config.externalLink[option]
            ? module.config.externalLink[option]
            : defaultValue;
    }

    var Content = Widget.extend();

    Content.prototype.modalMarkerId = 'legalExternalLinkModalMarker';

    Content.prototype.init = function() {
        const that = this;
        if (config('prefix') || config('confirmText')) {
            this.richtext().on('afterRender', function () {
                that.processExternalLinks();
            });
            this.initAutoRedirect();
        }
    };

    Content.prototype.richtext = function() {
        return this.$.find('[data-ui-richtext]');
    }

    Content.prototype.findExternalLinks = function() {
        return this.richtext().find('a:not([href^="' + location.origin + '"]):not([href^="#"]):not([href^="/"])');
    }

    Content.prototype.processExternalLinks = function() {
        if (typeof module.config.externalLink === 'undefined') {
            return;
        }

        const that = this;
        this.findExternalLinks().each(function() {
            if (config('prefix')) {
                $(this).html(config('prefix') + $(this).html());
            }
            if (config('confirmText')) {
                const modalMarker = '<i id="' + that.modalMarkerId + '"></i>';
                $(this).attr('data-action-confirm-header', config('confirmTitle'))
                    .attr('data-action-confirm', config('confirmText') + modalMarker)
                    .attr('data-action-confirm-text', config('confirmButton'));
            }
        });
    }

    Content.prototype.initAutoRedirect = function() {
        const that = this;
        let redirectAfter = config('redirectAfter');

        if (!redirectAfter) {
            return;
        }

        let redirectTimeInterval;

        $('#globalModalConfirm').on('shown.bs.modal', function () {
            if ($(this).find('#' + that.modalMarkerId).length) {
                redirectAfter = config('redirectAfter');
                const button = $(this).find('[data-modal-confirm]');
                button.data('html', button.html());
                redirectTimeInterval = setInterval(function () {
                    if (redirectAfter <= 0) {
                        button.trigger('click');
                    }
                    button.html(button.data('html') + ' (' + (redirectAfter--) + ')');
                }, 1000);
            }
        }).on('hidden.bs.modal', function () {
            if ($(this).find('#' + that.modalMarkerId).length) {
                clearInterval(redirectTimeInterval);
            }
        });
    }

    module.export({
        Content
    });
});