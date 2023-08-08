var currentSortField = 'created_at';
var currentSortOrder = 'desc';


$(document).ready(function() {
    loadFeedbacks();


    $("#feedbackForm").on("submit", function(event) {
        event.preventDefault();
        var formData = new FormData(this);



        $.ajax({
            url: "save_feedback.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    loadFeedbacks();
                    $("#feedbackForm")[0].reset();
                } else {
                    $("#error-message").text(response.message);
                    setTimeout(function() {
                        $("#error-message").text(""); // Очищаем текст сообщения
                    }, 5000);
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });
});

function loadFeedbacks() {
    $.ajax({
        url: "get_feedbacks.php",
        type: "GET",
        dataType: "json",
        success: function(response) {
            if (response.success) {
                displayFeedbacks(response.feedbacks);
            } else {
                console.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
}

function displayFeedbacks(feedbacks) {
    var feedbacksContainer = $("#feedbacksContainer");
    feedbacksContainer.empty();

    feedbacks.sort((a, b) => {
        if (currentSortOrder === 'asc') {
            return a[currentSortField].localeCompare(b[currentSortField]);
        } else {
            return b[currentSortField].localeCompare(a[currentSortField]);
        }
    });

    $.each(feedbacks, function(index, feedback) {
        var dateCreated = new Date(feedback.created_at); 
        var formattedDate = formatDate(dateCreated); 
        var editedByAdminMessage = feedback.edited_by_admin == 1 ? "<p><strong>Edited by admin</strong></p>" : "";
        var html = `
            <div class="feedback">
                <p><strong>Name:</strong> ${feedback.name}</p>
                <p><strong>Email:</strong> ${feedback.email}</p>
                <p><strong>Message:</strong> ${feedback.message}</p>
                ${editedByAdminMessage}
                <p><strong>Date:</strong> ${formattedDate}</p>
                <p><strong>Image:</strong> <img src="data:image/jpeg;base64,${feedback.image}"></p>
            </div>
        `;

        feedbacksContainer.append(html);
    });
}

function formatDate(date) {
    // YYYY-MM-DD HH:MM:SS
    var year = date.getFullYear();
    var month = padZero(date.getMonth() + 1);
    var day = padZero(date.getDate());
    var hours = padZero(date.getHours());
    var minutes = padZero(date.getMinutes());
    var seconds = padZero(date.getSeconds());
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function padZero(number) {
    return (number < 10 ? "0" : "") + number;
}

function sortFeedbacks(field) {
    if (currentSortField === field) {
        currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortField = field;
        currentSortOrder = 'asc';
    }
    loadFeedbacks();
}
