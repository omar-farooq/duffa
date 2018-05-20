# duffa

# example can be found at https://omar.earth/duffa

I was a bit reluctant to do this in the end because the duffa website as it is is working fine, but it's more just to add a couple of mobile friendly things and offer a couple of features. I still think that the current duffa website is fine as it is.

# ##############################################

 # features added on top of original website: 
  
# ##############################################
* A separate fancy front page/about page
* big menu for mobile views
* the banner on the top can easily be used to display sponsors/sponsor information which is why it was implemented this way. It saves having it down the sides and can be changed on each page to allow for different alerts/sponsors.
* the banner at the top can have multiple banners that you can swipe through which is useful for important information/events
* one first thing that you may miss is that you can upload pictures and make comments on a date that you attend a session. You need to click on the date on the timetable.
* uploaded user images go straight to a gallery and can also be posted in a comments section.
* swipe events on images - including news articles
* register, either with facebook or using a reg form, so that you can set your mailing preferences/make comments/buy things
* confirm your email address before updates are sent to you (unless registering via facebook then your email address is automatically verified)
* facebook login
* shop with a cart and paypal checkout (may be unwanted so can be changed but would be incredibly useful at events like the hat)
* admin section includes an ability to update the schedule with a GUI. This means that anyone with an admin account can update the schedule and puts less pressure on the person who knows how to use the database
* admin section has features to add a news article and add/remove items from the shop and view order items very easily
* admin sections allows to upload multiple images and then puts them in a gallery easily
* ability to send messages to people in duffa and receive them which is useful if the person doesn't use facebook.
* ability to send messages/notifications through comments by mentioning @username

** this is in no way a replacement for facebook, just an alternative for people who don't use it/frequently miss messages made on facebook due to them getting lost in a sea of messages. 
** Ideally, with an access token for the facebook group, we could have people use facebook as normal but also allow people who don't use facebook the ability to see what is going on

# ###################################################

 # Features in the code but not on my personal site 
 
# ###################################################
* cookie implementation for remembering someone - works with the code here now but not on my site due to older code on my site.
* doctype not declared on my site but is declared here in the code
* the 'content' always goes down to the footer in the code but not on the site
* the news front page wrapper is a flex box now rather than using javascript to make the height of the related section the same as the content
* my site has limitations for file upload sizes due to the free host that I use.


# ##########################################################

# things that should be added with the right permissions: 
 
# ##########################################################

** the site shouldn't act as a replacement for facebook in any way but should be 'merged' in a way that people who don't use facebook can interact with people who do via the site

* Facebook Graph allows you to get information from a group - such as events, images etc. With a permanent access token from an admin of the group we could change the social media section to show things like facebook posts/upcoming events etc.
* the facebook 'plugin' on the news page could either be implemented by making the facebook group open or just using the graph api to get the latest posts and display them natively

# #########################################

# Features that should be added/changed: 
 
# #########################################
* emailing in its current form isn't the best, particularly as the host for omar.earth seems to have disabled php's mail(). 
PEAR mail would be a better solution but the current back end solution on the main website works so I'd stick with that
* haven't yet added a feature to show that items from the shop have been posted (as not sure yet if the shop feature is wanted)
* an order from the shop can be made but it doesn't yet alert anyone as of yet (reasons as above)
* no post limit on making comments/registration as of yet (mainly due to testing; a post limit would be annoying)
* honeypot method or captcha has not yet been implemented for things like anonymous posts or registrations. This isn't really needed either unless bots/spam become an issue
* images used aren't the best as I don't have access to the duffa gallery/haven't found a good gallery/gallery may be on facebook which I don't use
* news section only displays the last three articles. I could easily change this so that you can keep swiping through older articles or put an archive in
* add a cover image option for user profiles
* ability to change your username
* about section is incomplete (but trivial)
* commenting @username currently leads to a blank user page. Either I should change the blank user page to read 'no such user' or not include a hyperlink to @non_existent_username
 

# ##############################

 # What the future should hold 
  
# ##############################
** features should be added for the next hat tournament
* include a chat feature called 'hat chat' using node.js and a websocket so that everyone in the hat is in one chat
* then have sub chats within this so that people can chat individually/in groups/on matches
* people signed up to the hat can create a profile and then 'create' things on their team's page such as their own logo, add pictures, discuss tactics privately etc.
perhaps done with react.js to allow for a smoother experience
* the project should be open source so that the present and the future can change the code to how they want.
