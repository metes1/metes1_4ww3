Name: Seda Mete
MAC ID: metes1
Student Number: 400134659

Link to website (to the deployed server with SSL enabled):
https://4ww3seda.live/bookshopper

Link to github repo:
https://github.com/metes1/metes1_4ww3

Part 3 Core Programming Tasks:
Below I list the main places where the new tasks for part three were implemented (their file path). I completed all tasks.

1) Search form (search.php)
    - Search form submits the search query and gets the results from the database (searchSubmit.php)
    - Dynamically generated results page (results_sample.php)

2) Dynamically generated results page (results_sample.php, searchSubmit.php)
    - Results are dynamically generated based on the search query submitted from the search form
    - Dynamically generated map and markers using the search results data from the database (js/maps.js)

3) Dynamically generated individual object page (individual_sample.php)
    - Dynamically generated map (js/maps.js)
    - Logged in user can submit a review and rating (individual_sample.php, reviewSubmit.php)

4) Object submission page (submission.php, objSubmit.php)
    - Object submission page submits user's data to php script, performs validation and adds to database (objSubmit.php)
    - Only logged in users can access page (if session shows user isn't logged in, the submission link does not appear and also the submission page redirects to index.php if logged out user tries to access by url)
    -Users can upload images, they are stored using Amazon's S3 bucket

5) User registration page (registration.php, regSubmit.php)
    -  Server-side validation and inserts new user into database (regSubmit.php)






