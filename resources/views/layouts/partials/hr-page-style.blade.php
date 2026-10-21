<style>
    .hr-page input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]),
    .hr-page select,
    .hr-page textarea {
        border-width: 1px;
        border-style: solid;
        border-color: #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        background-color: #fff;
        color: #111827;
        min-height: 40px;
    }
    .hr-page textarea { min-height: 88px; }
    .dark .hr-page input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]),
    .dark .hr-page select,
    .dark .hr-page textarea {
        background-color: #374151;
        border-color: #4b5563;
        color: #f9fafb;
    }
    .hr-page input:focus,
    .hr-page select:focus,
    .hr-page textarea:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.35);
        border-color: #2563eb;
    }
    .hr-page label:not(.inline-flex):not(.flex) {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.25rem;
    }
    .dark .hr-page label { color: #d1d5db; }
    .hr-page thead tr { background: #f9fafb; }
    .dark .hr-page thead tr { background: #374151; }
    .hr-page th {
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        color: #6b7280;
        padding: 0.75rem 1rem;
        white-space: nowrap;
    }
    .dark .hr-page th { color: #d1d5db; }
    .hr-page td {
        padding: 0.75rem 1rem;
        color: #111827;
        vertical-align: middle;
    }
    .dark .hr-page td { color: #e5e7eb; }
    .hr-page tbody tr { border-top: 1px solid #f3f4f6; }
    .dark .hr-page tbody tr { border-top-color: #374151; }
    .hr-page .bg-white {
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }
    .dark .hr-page .bg-white,
    .dark .hr-page .dark\:bg-gray-800 {
        border-color: #374151;
    }
    .dark .hr-page .bg-green-50 {
        background: rgba(6, 78, 59, 0.35);
        color: #d1fae5;
    }
    .dark .hr-page .bg-red-50 {
        background: rgba(127, 29, 29, 0.35);
        color: #fecaca;
    }
</style>
