// resources/js/app.js

import Chart from 'chart.js/auto';
window.Chart = Chart;

// =====================
// Custom toast function
// =====================
function getToastContainer() {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.position = 'fixed';
        container.style.top = '1rem';
        container.style.right = '1rem';
        container.style.display = 'flex';
        container.style.flexDirection = 'column';
        container.style.gap = '0.5rem';
        container.style.zIndex = 9999;
        document.body.appendChild(container);
    }
    return container;
}

window.toast = (message, type = 'success', duration = 4000) => {
    const colors = {
        success: '#48bb78',
        error: '#f56565',
        warning: '#ed8936',
        info: '#4299e1',
    };

    const container = getToastContainer();

    const toastEl = document.createElement('div');
    toastEl.style.cssText = `
        position: relative;
        background: ${colors[type] || colors.success};
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        opacity: 0;
        transform: translateX(120%);
        transition: opacity 0.4s ease, transform 0.4s ease;
        overflow: hidden;
        min-width: 220px;
        max-width: 380px;
    `;

    if (Array.isArray(message)) {
        // Close button — dahil hindi na auto-disappear ang mahabang toast
        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.innerHTML = '&times;';
        closeBtn.style.cssText = `
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.1rem;
            line-height: 1;
            cursor: pointer;
            opacity: 0.8;
            padding: 0;
        `;
        closeBtn.addEventListener('click', () => {
            toastEl.style.opacity = '0';
            toastEl.style.transform = 'translateX(120%)';
            setTimeout(() => toastEl.remove(), 400);
        });
        toastEl.appendChild(closeBtn);
        toastEl.style.paddingRight = '2rem';

        const [title, ...items] = message;
        const collapsedLimit = 5;
        const hasMore = items.length > collapsedLimit;

        const titleEl = document.createElement('div');
        titleEl.textContent = title;
        titleEl.style.cssText = `
            font-weight: 600;
            margin-bottom: 0.5rem;
        `;
        toastEl.appendChild(titleEl);

        const listEl = document.createElement('ul');
        listEl.style.cssText = `
            margin: 0;
            padding-left: 1.1rem;
            list-style-type: disc;
            max-height: 120px;
            overflow-y: auto;
        `;

        const renderItems = (list, limit) => {
            listEl.innerHTML = '';
            const shown = limit ? list.slice(0, limit) : list;
            shown.forEach((itemText) => {
                const li = document.createElement('li');
                li.textContent = itemText;
                li.style.cssText = `
                    font-size: 0.8125rem;
                    line-height: 1.5;
                    margin-bottom: 0.15rem;
                `;
                listEl.appendChild(li);
            });
        };

        renderItems(items, hasMore ? collapsedLimit : null);
        toastEl.appendChild(listEl);

        if (hasMore) {
            const toggleEl = document.createElement('button');
            toggleEl.type = 'button';
            toggleEl.textContent = `Show ${items.length - collapsedLimit} more \u25BE`;
            toggleEl.style.cssText = `
                background: none;
                border: none;
                color: white;
                text-decoration: underline;
                font-size: 0.75rem;
                font-weight: 600;
                cursor: pointer;
                padding: 0.375rem 0 0;
                margin: 0;
            `;

            let expanded = false;
            toggleEl.addEventListener('click', () => {
                expanded = !expanded;
                if (expanded) {
                    renderItems(items, null);
                    listEl.style.maxHeight = '200px';
                    toggleEl.textContent = 'Show less \u25B4';
                } else {
                    renderItems(items, collapsedLimit);
                    listEl.style.maxHeight = '120px';
                    toggleEl.textContent = `Show ${items.length - collapsedLimit} more \u25BE`;
                }
            });

            toastEl.appendChild(toggleEl);
        }

        // Toast will not auto-remove itself while it has an expandable list —
        // it stays until the user closes it or a fresh toast pushes it out.
        duration = hasMore ? 999999 : duration;
    } else {
        toastEl.textContent = message;
    }

    const progress = document.createElement('div');
    progress.style.cssText = `
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        background: rgba(255,255,255,0.8);
        width: 100%;
        transition: width ${duration}ms linear;
    `;
    toastEl.appendChild(progress);

    container.appendChild(toastEl);

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toastEl.style.opacity = '1';
            toastEl.style.transform = 'translateX(0)';
            progress.style.width = '0%';
        });
    });

    setTimeout(() => {
        toastEl.style.opacity = '0';
        toastEl.style.transform = 'translateX(120%)';
    }, duration - 400);

    setTimeout(() => toastEl.remove(), duration);
};

document.addEventListener('alpine:init', () => {
    Alpine.data('flatpickrFilter', (wire, filterKey, configs, elRefIgnored, locale) => ({
        wireValues: wire.entangle('filterComponents.' + filterKey),
        flatpickrInstance: null,

        init() {
            this.$nextTick(() => {
                const el = this.$refs.dateRangeInput;
                if (!el) return;

                // Kung may existing flatpickr na naka-attach sa element (edge case),
                // gamitin na lang iyon sa halip na gumawa ng duplicate.
                if (el._flatpickr) {
                    this.flatpickrInstance = el._flatpickr;
                } else {
                    this.flatpickrInstance = flatpickr(el, {
                        mode: 'range',
                        altFormat: configs.altFormat ?? 'F j, Y',
                        altInput: configs.altInput ?? false,
                        allowInput: configs.allowInput ?? false,
                        allowInvalidPreload: configs.allowInvalidPreload ?? true,
                        ariaDateFormat: configs.ariaDateFormat ?? 'F j, Y',
                        clickOpens: true,
                        dateFormat: configs.dateFormat ?? 'Y-m-d',
                        defaultDate: configs.defaultDate ?? null,
                        defaultHour: configs.defaultHour ?? 12,
                        defaultMinute: configs.defaultMinute ?? 0,
                        enableTime: configs.enableTime ?? false,
                        enableSeconds: configs.enableSeconds ?? false,
                        hourIncrement: configs.hourIncrement ?? 1,
                        locale: configs.locale ?? locale ?? 'en',
                        minDate: configs.earliestDate ?? null,
                        maxDate: configs.latestDate ?? null,
                        minuteIncrement: configs.minuteIncrement ?? 5,
                        shorthandCurrentMonth: configs.shorthandCurrentMonth ?? false,
                        time_24hr: configs.time_24hr ?? false,
                        weekNumbers: configs.weekNumbers ?? false,
                        onOpen: () => {
                            window.childElementOpen = true;
                        },
                        onChange: (selectedDates, dateStr) => {
                            if (selectedDates.length > 1) {
                                const parts = dateStr.split(' ');
                                window.childElementOpen = false;
                                window.filterPopoverOpen = false;
                                wire.set('filterComponents.' + filterKey, {
                                    minDate: parts[0],
                                    maxDate: parts[2] === undefined ? parts[0] : parts[2],
                                });
                            }
                        },
                    });
                }

                this.setupWire();
                this.$watch('wireValues', () => this.setupWire());
            });
        },

        changedValue(val) {
            if (val.length < 5 && this.flatpickrInstance) {
                this.flatpickrInstance.setDate([]);
                wire.set('filterComponents.' + filterKey, {});
            }
        },

        setupWire() {
            if (!this.flatpickrInstance) return;

            if (this.wireValues !== undefined
                && this.wireValues.minDate !== undefined
                && this.wireValues.maxDate !== undefined) {
                this.flatpickrInstance.setDate([this.wireValues.minDate, this.wireValues.maxDate]);
            } else {
                this.flatpickrInstance.setDate([]);
            }
        },
    }));
});
