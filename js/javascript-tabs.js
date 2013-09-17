
var tabLinks = new Array(); //stores each tab link element 
var contentDivs = new Array(); //stores div content of each associated tab link

/*
 * Initializes the tab
 * This is to be called when the page loads.
 */
function init() 
{
   // Grab the tab links and content divs from the page
   var tabListItems = document.getElementById('tabs').childNodes;
   for ( var i = 0; i < tabListItems.length; i++ ) 
   {
     if ( tabListItems[i].nodeName == "LI" ) 
     {
       var tabLink = getFirstChildWithTagName( tabListItems[i], 'A' ); // call helper function
       var id = getHash( tabLink.getAttribute('href') ); // call helper function
       tabLinks[id] = tabLink;
       contentDivs[id] = document.getElementById( id );
     }
   }

   // Assign onclick events to the tab links, and
   // highlight the first tab
   var i = 0;

   for ( var id in tabLinks ) 
   {
     tabLinks[id].onclick = showTab; //attach helper function to onclick event
     tabLinks[id].onfocus = function() { this.blur() };
     if ( i == 0 ) tabLinks[id].className = 'selected';
     i++;
   }

   // Hide all content divs except the first
   var i = 0;

   for ( var id in contentDivs ) 
   {
     if ( i != 0 ) contentDivs[id].className = 'tabContent hide';
     i++;
   }
 }

/*
 * A helper function to be attached to an onclick event.
 */
function showTab() 
{
  var selectedId = getHash( this.getAttribute('href') );

  // Highlight the selected tab, and dim all others.
  // Also show the selected content div, and hide all others.
  for ( var id in contentDivs )
  {
    if ( id == selectedId ) 
    {
        //Set css classname; Highlight case
        tabLinks[id].className = 'selected';
        contentDivs[id].className = 'tabContent';
    } 
    
    else 
    {
        //Set css classname; Dim case
        tabLinks[id].className = '';
        contentDivs[id].className = 'tabContent hide';
    }
  }

  // Stop the browser following the link
  return false;
}

/*
 * This helper function returns the first child of a 
 * specified element that matches a specified tag name
 */
function getFirstChildWithTagName( element, tagName ) 
{
  for ( var i = 0; i < element.childNodes.length; i++ ) 
  {
    if ( element.childNodes[i].nodeName == tagName ) return element.childNodes[i];
  }
  return null;
}

/*
 * Helper function returns the portion of
 *  a URL after any hash symbol. 
 */
function getHash( url ) 
{
  var hashPos = url.lastIndexOf ( '#' );
  return url.substring( hashPos + 1 );
}