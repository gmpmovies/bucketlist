$(window).scroll(function () {
    const feed_data = $('#feed-content-data-tags');
    const query = feed_data.data('query');
    const wait = feed_data.data('wait');
    // End of the document reached?
    if ($(document).height() - $(this).height() - 3000 <= $(this).scrollTop() && query === "True" && wait === "False") {
        retrieveContent();
    }
});

$(document).ready(function () {
    retrieveContent();
});

function retrieveContent(){
    const feed_data = $('#feed-content-data-tags');
    const offset = feed_data.data('offset');
    const loc = feed_data.data('loc');
    var userid = "";
    if(loc === "profile"){
        let searchParams = new URLSearchParams(window.location.search);
        userid = searchParams.get('userid')
    }

    $.ajax({
        type: "GET",
        url: "/bucketlist/actions/get-posts.php",
        contentType: "application/json; charset=utf-8",
        data: {offset: offset, loc: loc, userid: userid},

        beforeSend(jqXHR, settings) {
            feed_data.data("wait", "True");
        },

        success: function (msg) {
            if (msg === "False"){
                $('.feed-loading-indicator').addClass('d-none');
                $('.feed-end-of-feed').removeClass('d-none').addClass('d-block');
                feed_data.data("query", "False");
            } else {
                const $feedContainer = $(".infinite-feed-container");
                const content = $feedContainer.append(msg);
                const newContent = content.find('#feed-batch-' + offset);

                newContent.find('.all-posts').on('init', function(event, slick){
                    setTimeout(function(){newContent.find('.slick-list').css('height', 'auto');}, 100);
                });

                newContent.find('.all-posts').slick({
                    adaptiveHeight: true,
                    dots: true,
                    mobileFirst: true,
                });
                newContent.find('.post-text').collapser({
                    mode: 'words',
                    truncate: 20
                });
                newContent.find('[data-toggle="popover"]').popover();
                newContent.find('.slick-list').css('height', 'auto');
                feed_data.data("offset", offset + 1);
                feed_data.data("wait", "False");
            }
        },
        error: function (req, status, error) {
            feed_data.data("wait", "False");
            console.log("Error try again");
        }
    });
}


$(function () {
    $('[data-toggle="popover"]').popover();
});

// $(document).ready(function(){
//     $('.post-text').collapser({
//         mode: 'words',
//         truncate: 20
//     });
// });
//
// $('.post-text').collapser({
//     mode: 'words',
//     truncate: 20
// });
//
// $(document).ready(function(){
//     $('.all-posts').slick({
//         adaptiveHeight: true,
//         dots: true,
//         mobileFirst: true,
//     });
// });

$(document).on('click', ".feed-video-thumbnail-container", function(){
    const videoItem = $(this).parent(); //Get parent element
    const embedContainer = videoItem.find(".feed-video-embed");//Get video embed container
    const width = videoItem.width(); //Get video container width
    const height = width/1.778; //Calculate video height
    const videoID = embedContainer.data('videoid'); //Get YouTube videoID
    videoItem.find('.feed-video-thumbnail-container').addClass('d-none'); //Hide the thumbnail

    const iframe = '<iframe class="feed-video" src="//www.youtube.com/embed/' + videoID + '?modestbranding=1&autoplay=1&mute=1&enablejsapi=1" height="' + height + '" allow="encrypted-media" frameborder="0" allowfullscreen>\n' +
        '</iframe>';
    embedContainer.append(iframe);

    const postid = embedContainer.data('postid'); //get the unique post id
    const currentPost = $("." + postid);
    const textHeight = currentPost.find('.feed-text-height').height();
    currentPost.parent().parent().css('height', height + textHeight + 20);

    embedContainer.addClass('d-block').removeClass('d-none');
});

$(document).on('click', '.feed-expand-container', function(){
    const postid = $(this).data('postid');
    const currentPost = $("." + postid);
    const textHeight = currentPost.find('.feed-text-height').height();
    const imageHeight = currentPost.find('.feed-image').height();
    currentPost.parent().parent().css('height', imageHeight + textHeight + 10);
});

// $(".feed-video-thumbnail-container").on("click", function(){
//     const videoItem = $(this).parent();
//     const embedContainer = videoItem.find(".feed-video-embed");
//     const videoID = embedContainer.data('videoid');
//     videoItem.find('.feed-video-thumbnail-container').addClass('d-none');
//     const iframe = '<iframe class="feed-video" src="//www.youtube.com/embed/' + videoID + '?modestbranding=1&autoplay=1" frameborder="0" allowfullscreen>\n' +
//         '</iframe>';
//     embedContainer.append(iframe);
//
//
//     embedContainer.addClass('d-block').removeClass('d-none');
//
// });
