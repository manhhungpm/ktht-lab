export default {
    table: {
        phone_number: "Phone number",
        calls_total: "Number of calls",
        carrier: "Carrier",
        time_range: "Time range",
        duration_total: "Total duration (mins)",
        spam_labeled_calls: "Spam labeled calls",
        not_spam_labeled_calls: "Not spam labeled calls",
        unlabeled_calls: "Unlabeled calls",
        status: "Status",
        spam_total: "Total number of spam labeled calls: ",
        fee_total: "Total calling fees:"
    },
    status: {
        suggest: "Suggest lock",
        locked: "Locked",
        unlocked: "Unlocked",
        bypassed: "Bypassed"
    },
    action: {
        lock: "Lock",
        unlock: "Unlock",
        bypass: "Bypass"
    },
    msg: {
        confirm_lock:
            "Are you sure to <span class='text-danger'> lock </span> phone number <span class='text-danger'> {number} </span> ?",
        confirm_unlock:
            "Are you sure to <span class='text-success'> unlock </span> phone number <span class='text-danger'> {number} </span> ?",
        confirm_bypass:
            "Are you sure to <span class='text-info'> bypass </span> phone number <span class='text-danger'> {number} </span> ?"
    },
    placeholder: {
        phone_number: "Search for a phone number",
        time_range: "Select a time range",
        carrier: "Select a carrier",
        status: "Select status(es)"
    },
    carrier: {
        other: "Other",
        all: "All"
    }
};
