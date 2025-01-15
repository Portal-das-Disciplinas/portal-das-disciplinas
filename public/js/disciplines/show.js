const incrementDecrementStep = 0.25;

function incrementPlaybackRate(){
    let audioPodcast = document.querySelector('#podcast');
    if(audioPodcast.playbackRate < 2.0){
        audioPodcast.playbackRate += incrementDecrementStep;
        updatePlaybackRateInfo();
    }
}

function decrementPlaybackRate(){
    let audioPodcast = document.querySelector('#podcast');
    if(audioPodcast.playbackRate > 0.5){
        audioPodcast.playbackRate -= incrementDecrementStep;
        updatePlaybackRateInfo();
    }
}

function updatePlaybackRateInfo(){
    let audioPodcast = document.querySelector('#podcast');
    let podcastInfo = document.querySelector('#podcast-playback-rate');
    podcastInfo.innerHTML = audioPodcast.playbackRate + "x";

}

