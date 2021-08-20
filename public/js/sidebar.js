function move(active) {
    let sidebar = document.getElementById('sidebar');
    if(active){
        sidebar.classList.remove('left')
        sidebar.classList.add('right')
    }else{
        sidebar.classList.remove('right')
        sidebar.classList.add('left')
    }
}