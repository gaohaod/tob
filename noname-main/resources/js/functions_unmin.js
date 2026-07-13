
/*
    This is a global set of scripts that can be embedded anywhere to do a function. This is better and cleaner than having 50 JS files for specific functions (buying, joining, etc.)
    ectoBiologist - 9/7/2024 - 5:44PM
*/

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}


function registerPresence() {
  if (!document.hidden) {
    fetch('/app/presence')
      .then((resp) => resp.json())
      .then(() => {
        console.log("[Presence] Registered presence.");
      })
      .catch((err) => {
        console.error("[Presence] Failed to register presence:", err);
      });
  } else {
    console.log("Sorry, not feeling it rn");
  }
}


async function buySlot() {
let modalBuyButton = document.getElementById('modal-buy-button');
let modalMessage = document.getElementById('modal-message');
let modal = document.getElementById('modal-container');
const originalModalMessage = modalMessage.innerHTML;
const originalModal = modal;

modalMessage.innerHTML = 'Purchasing slot...';
modalBuyButton.disabled = true;

// onclick="refresh()"
// data-dismiss="modal"


const buyEndpoint = `/app/buy-slot`;
try {
    const response = await fetch(buyEndpoint);
    const result = await response.json();

    if (!response.ok) {
        console.log('[PaymentModule] - failed to buy slot, http error');
        modalMessage.innerHTML = 'Failed to purchase slot. Please try again.';
        // refresh the button so it doesn't refresh the page nvm that was stupid as fuck
        modalBuyButton.disabled = false;
        await sleep(5000);
        modalMessage.innerHTML = originalModalMessage; // todo: already did this
    } else if (result.success === true) { 
        modalMessage.innerHTML = result.message;
        modalBuyButton.remove();
        console.log('[PaymentModule] - payment finished ok refreshing!!!');
        modalBuyButton.remove();
    } else if (result.success === false) {
        modalMessage.innerHTML = result.message;
        await sleep(5000);
        modalMessage.innerHTML = originalModalMessage; 
        modalBuyButton.disabled = false;
    }
} catch (error) {
    console.log('[PaymentModule] - error:', error);
    modalMessage.innerHTML = 'Failed to buy slot. Please try again later.';
    modalBuyButton.disabled = false;
}
}


async function buyItem(id) {
  let modalBuyButton = document.getElementById('modal-buy-button');
  let modalMessage = document.getElementById('modal-message');
  let modal = document.getElementById('modal-container');
  let modalCustomizeButton = document.getElementById('modal-customize-button');
  const originalModalMessage = modalMessage.innerHTML;
  const originalModal = modal;

  modalMessage.innerHTML = 'Purchasing items...';
  modalBuyButton.disabled = true;
  
  // onclick="refresh()"
  // data-dismiss="modal"
  
  const buyEndpoint = `/app/buy-item/${id}`;
  try {
      const response = await fetch(buyEndpoint);
      const result = await response.json();

      if (!response.ok) {
          console.log('[PaymentModule] - failed to buy item, http error');
          modalMessage.innerHTML = 'Failed to purchase item. Please try again.';
          // refresh the button so it doesn't refresh the page nvm that was stupid as fuck
          modalBuyButton.disabled = false;
          await sleep(5000);
          modalMessage.innerHTML = originalModalMessage; // todo: already did this
      } else if (result.success === true) { 
          modalMessage.innerHTML = result.message;
          modalBuyButton.remove();
          console.log('[PaymentModule] - payment finished ok refreshing!!!');
          modalCustomizeButton.classList.remove('hidden');
      } else if (result.success === false) {
          modalMessage.innerHTML = result.message;
          await sleep(5000);
          modalMessage.innerHTML = originalModalMessage; 
          modalBuyButton.disabled = false;
      }
  } catch (error) {
      console.log('[PaymentModule] - error:', error);
      modalMessage.innerHTML = 'Failed to buy item. Please try again later.';
      modalBuyButton.disabled = false;
  }
}


function parseDescription() {
  let desc = document.getElementById('description-parse').innerHTML;

  let parsed = desc.replace(/#W/g, '<br>');
  parsed = parsed.replace(/#B (.+?) #\/B/g, '<b>$1</b>');
  parsed = parsed.replace(/#I (.+?) #\/I/g, '<i>$1</i>');
  document.getElementById('description-parse').innerHTML = parsed;

  console.log(parsed);
}

function refresh() {
  console.log('[functionsjs] - refreshing page!!!!!!!!!');
  return location.reload();
}

// This is from stackoverflow #lazy
function abbreviate(number, maxPlaces, forcePlaces, forceLetter) {
  number = Number(number)
  forceLetter = forceLetter || false
  if(forceLetter !== false) {
    return annotate(number, maxPlaces, forcePlaces, forceLetter)
  }
  var abbr
  if(number >= 1e12) {
    abbr = 'T'
  }
  else if(number >= 1e9) {
    abbr = 'B'
  }
  else if(number >= 1e6) {
    abbr = 'M'
  }
  else if(number >= 1e3) {
    abbr = 'K'
  }
  else {
    abbr = ''
  }
  return annotate(number, maxPlaces, forcePlaces, abbr)
}

function annotate(number, maxPlaces, forcePlaces, abbr) {
  // set places to false to not round
  var rounded = 0
  switch(abbr) {
    case 'T':
      rounded = number / 1e12
      break
    case 'B':
      rounded = number / 1e9
      break
    case 'M':
      rounded = number / 1e6
      break
    case 'K':
      rounded = number / 1e3
      break
    case '':
      rounded = number
      break
  }
  if(maxPlaces !== false) {
    var test = new RegExp('\\.\\d{' + (maxPlaces + 1) + ',}$')
    if(test.test(('' + rounded))) {
      rounded = rounded.toFixed(maxPlaces)
    }
  }
  if(forcePlaces !== false) {
    rounded = Number(rounded).toFixed(forcePlaces)
  }
  return rounded + abbr
}

function parseForum() {
  let forumParse = document.getElementById('forum-parse');
  if (forumParse) {
      forumParse.innerHTML = forumParse.innerHTML.replace(/#misinformation/g, '<img src="/images/misinformation.gif" width="100"><br>');
  }

  document.querySelectorAll('[id^="forum-reply-"]').forEach(function(replyElement) {
      replyElement.innerHTML = replyElement.innerHTML.replace(/#misinformation/g, '<img src="/images/misinformation.gif" width="100"><br>');
  });
}

// Lazy loading thingy
document.addEventListener("DOMContentLoaded", () => {
    const images = document.querySelectorAll('.lazy-load');

    images.forEach(img => {
        const actualSrc = img.getAttribute('data-src');
        let attempts = 0; 

        function loadImage() {
            const tempImg = new Image();
            tempImg.src = actualSrc;

            tempImg.onload = () => {
                img.src = actualSrc;
            };

            tempImg.onerror = () => {
                    console.error(`couldnt load image: ${actualSrc}`);
                    if (document.location.pathname === "/app/places") {
                      img.src = '/images/no_thumbnail.png'; 
                    } else {
                      img.src = '/images/confused.png'; 
                    }
                    
            };
        }

        loadImage(); 

    });
});



function disableButton(button) {
button.disabled = true;
}

setInterval(registerPresence, 60000);
registerPresence();