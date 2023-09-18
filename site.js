function $(str) {
  return document.querySelector(str);
}

function $all(str) {
  return document.querySelectorAll(str);
}

//Project Views
let projects = $('.projects');
let activeView = $('.project-wrapper.active');
let activeViewHeight = activeView.offsetHeight;
let viewSelects = $all('.views input');
let banner = $('.banner-outer');
projects.style.setProperty('--projectsHeight', activeViewHeight + "px");

window.addEventListener('load', () => {
  viewSelects.forEach((vs) => {
    vs.addEventListener('click', () => {
      if (vs.classList.contains('active') === false) {
        let queryString = ".project-" + vs.value;
        let newActiveView = $(queryString);
        removeClassAll(viewSelects, "active");
        vs.classList.add('active');
        activeView.classList.remove('active');
        newActiveView.classList.add('active');
        activeView = newActiveView;
        activeViewHeight = activeView.offsetHeight;
        // newActiveView.classList.contains('project-list') ? activeViewHeight += 200: null;
        projects.style.setProperty('--projectsHeight', activeViewHeight + "px");
        queryString.includes('list') ? closeAllAccordions() : null;
      }
    })
  });
});

if(banner) {
  setTimeout(() => { banner.classList.add('fadeout') }, 3000 );
  setTimeout(() => { banner.remove() }, 3350 );
}

function removeClassAll(els, clss) {
  els.forEach((el) => { el.classList.remove(clss) })
}


//Modals
let dialogContainers = $all('[dialog-container]');

function dOpen(el, target) {
    el.addEventListener('click', () => {
      target.showModal();
      target.classList.add('show')
    })
  }
function dClose(el, target) {
    el.addEventListener('click', () => {
      target.classList.remove('show');
      setTimeout(target.close(), 400)
    })
}

dialogContainers.forEach((c) => {
  let dialog = c.querySelector('.dialog');
  let dialogOpen = c.querySelector('.dialog-open');
  let dialogClose = c.querySelector('.dialog-close');
  let dialogFinish = c.querySelector('.dialog-finish');
  let dialogBackdrop = c.querySelector('.dialog-backdrop');

//   dialog.showModal();

//   dialogOpen.addEventListener('click', () => {
//     dialog.classList.add('show');
//   });
  dOpen(dialogOpen, dialog);


  dialogClose ? dClose(dialogClose, dialog) : null;
  dialogFinish ? dClose(dialogFinish, dialog) : null;
  dialogBackdrop ? dClose(dialogBackdrop, dialog) : null;
});



//Akkordion
let accordionItems = $all('.project-list .project-item');
let bonusHeight = false;
activeViewHeight = activeView.offsetHeight;

accordionItems.forEach(item => {
  const open = item.querySelector('.project-getinfo');
  const contentHeight = item.querySelector('.project-content-inner').offsetHeight+20;
  // const content = item.querySelector('.location-list-item-content');

  open.addEventListener('click', () => {
    if (!item.classList.contains('open')) {
      closeAllAccordions();
      item.classList.add('open');
      activeViewHeight += contentHeight;
      projects.style.setProperty('--projectsHeight', activeViewHeight + "px");
    } else {
      item.classList.remove('open');
      activeViewHeight -= contentHeight;
      projects.style.setProperty('--projectsHeight', activeViewHeight + "px");
    }
  });
});
// if(!bonusHeight) {
//         activeViewHeight += 250;
//         bonusHeight = true;
//       }
// if(bonusHeight) {
//         activeViewHeight -= 250;
//         bonusHeight = false;
//       }
function closeAllAccordions() {
  accordionItems.forEach(item => {
    item.classList.remove('open');
  });
}


// Delete Button
$all('.btn-delete').forEach((b) => {
  b.addEventListener("click", (e) => {
    b.classList.add('confirm');
  });
  b.addEventListener("mouseleave", (e) => {
    b.classList.remove('confirm');
  })
});


