// We're using jQuery throughout.

// $ is the jQuery object. $() lets us address DOM elements by identifier, tag name, class, or ID.
// The syntax is the same as CSS, except that we can also address DOM elements like document and window.
// If you use CSS syntax to identify an element, put it in single quotes. For instance, $('body') is the <body>
// tag, $('.bold') is any tag with "bold" in its classes, and $('#specific-bold-item') is the tag with the
// specific ID "specific-bold-item". Remember that you can only have one element with a given ID on any page.

// $(document).ready(); is jQuery's replacement for the onLoad attribute in the <body> tag. It loads once 
// everything in the document has been parsed. It's important to remember that nothing in here will take effect
// until the page is done loading; if someone interacts with the page before it's done loading, nothing in here 
// will have happened yet. If you have sanity checks in here, make sure there's a way to force the user to wait
// until the page loads before they submit their data.

$(document).ready(function() {

	// This is what happens when someone submits the form. The form should refuse to process unless there's
	// a name entered and unless the total bid is between <minval> and <maxval> (default 18, set below).


});

	$('.md_entry').submit(function(event) {
		alert("changed");  // purely diagnostic
		return false; event.preventDefault();// also diagnostic
		// These are inclusive; you can bid [minval] but not below, and you can bid [maxval] but not above.
		// To set a specific amount that the player MUST bid, set minval and maxval to the same value.
		var minval = 1; // Change this to change the minimum total buy-in value
		var maxval = 18; // Change to change the maximum total buy-in value
		// Initialize the current total value
		var totalval = 0;
		// Cycle through the <select> objects and get each current value
		$('.md_entry_item').each(function(i,obj) {
			totalval = totalval + parseInt($(this).val());
		});
		$('#md_entry_total_val').html(totalval); // Change the value of the "Current total" text to the current total value
//		alert(totalval); // purely diagnostic, comment this out once the rest of the code is working right
		// Now check totalval against minval and maxval
		// Both conditions must succeed for this to go off properly
		if(totalval >= minval && totalval <= maxval) {
			return; // Submit the form
		} else { // If at least one of the conditions in the if() failed
			event.preventDefault(); // Prevent the form from submitting
			// We stop submission first because if there's an error in the code below, it'll stop but 
			// won't invalidate anything above it. So this way at least they can't submit on a bad value.
			// Display different errors depending on which condition failed
			if(totalval < minval) { // If the bid isn't high enough
				alert("You must bid at least $" + minval + ".");
				$(this).val(previous).change(); // Set the value back to what it was before the change
			} else { // Only one other option - if the bid is too high
				alert("You may bid a maximum of $" + maxval + ".");
				$(this).val(previous).change(); // Set the value back to what it was before the change
			}
			return false; // if we return false, the <select> will refuse the change!
		}
		return true; // We can safely exit at this point without returning, but just in case, let's be explicit
	});