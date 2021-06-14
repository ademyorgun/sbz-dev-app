<template>
    <div class="comment">
        <div class="main">
            <div class="avatar">
                <img :src=" 'https://sbz-appointment.fra1.digitaloceanspaces.com/' + comment.avatar" alt="">
            </div>
            <div class="text">
                <a class="username" href="#">@{{ comment.user_username }}</a> <span class="comment-body" > {{ comment.body }}</span><span class="updated_at">{{ comment.updated_at }}</span>
            </div>
        </div>
        <div class="image">
            <img calass="feedback-image" style="max-width:100%" :src=" 'https://bucketeer-24961eed-3942-41da-b1d8-d4ce18c0f829.s3.amazonaws.com/' + comment.comment_image" alt="">
        </div>
    </div>
</template>

<script>
    export default {
        name: 'singleComment',
        props: ['comment']
    }
</script>

<style scoped>
/* Single-comment component */
.comment {
    display: flex;
    flex-flow: column nowrap;
    color: #333;
    background-color: #fff;
    border-radius: 4px;
    box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
    margin-top: 1.5rem;
}

.main{
    display: flex;
    padding: 10px;
    margin-bottom: 10px;
    align-items: center;
}

.comment .avatar {
    align-self: flex-start;
}

.comment .avatar > img {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    align-self: start;
}

.comment .text {
    text-align: left;
    margin-left: 5px;
    width: 100%;
    display: flex;
}

.image{
    margin-top: 1rem;
    max-width: 100%;    
}

.feedback-image{
    max-height: 700px;
    max-width: 100%;    
}
.comment .text .username {
    font-weight: 700;
    color: #76838f;
}

.comment-body{
    max-width: 70%;
    margin-left:10px;
}

.comment .updated_at{
    margin-left: auto;
}
</style>
