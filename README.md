# mscprojectweb
Masters Final Year Project (Dissertation)



**PROJECT WORKFLOW:**

User Register into the system
User login
User submits form. The customer can submit multiple requests. They can edit and update the request until it is assigned to a technician. If the request is assigned, then the customer cannot update their submitted request. They need to submit the new request form. The customer submits forms based on their problem. They can choose the category and the service type. The customer can also mention whether their request is of high priority or normal. The customer with the high-priority request is prioritized, and this can cost a little more for them. This approach is used to justify prioritizing the customer request.
After the user submits the form. They get the notification via email with their details along with the unique request id. Through this id they can track the status of their request.
Then the admin receives the request. They can either approve or reject the request. If rejected, the customer gets notified of the rejection. If approved, the customer receives a notification that the admin has approved their request, and they now need to wait for the technician assignment.
Admin creates the technician account, and the technician receives their account credentials via their email notification.
Admin assigns the approved request to the technician. Admin can view the rating and availability of the technician. The rating is determined by the technician's work completion of the technician. The more work completed, the higher the technician's rating, and the technician appears at the top automatically while assigning the request. Admin can also see whether the technician is available or busy with previous work. If the technician is available and assigned, then both the technician and user get an email notification. The user can know the details of the technician assigned to their request, and the technician can view the details of the customer as well.
If the technician is busy and a request is assigned by the admin, then both the user and technician receive the notification via email. But here is a slight difference, i.e. customer receives the notification that the technician that your request is assigned to is busy and will let you know when he is free (available) and will start on your request. For the technician notification is the same; he gets the notification of the customer details on which request he/she is assigned. Both a technician and a user can view these details in their respective portals.
When the technician is available after completing the previous engaged work, the customer receives notification via email that the assigned technician is free now from previous work and is now dealing with the customer's newly assigned work request.
After the request completion, the technician marks or clicks as complete, and again, both the admin and user get notified that the request is being completed.
Hence, the invoice of the user is generated automatically by the system.
The technician logs in to the system using the credentials sent to their email by the admin.
The technician can view their assigned task. They can also view the details of the customer.



**USER**

User Register into the system
User login
The user can view and update their profile
The user can submit the request
The user can view the submitted request
The user can track their request. They can view their request status, either assigned to a technician, not assigned, or completed.
They get the notification of technician assignment and request completion via email.
The user can log out of the system

**TECHNICIAN**

Admin creates the login credentials of the technician. Admin creates the account for the admin, and the technician's login credentials are automatically sent to them via their email.
A technician can login into the system
Technician can view and update their profile
The technician can view their assigned task and request details. They can also view the details of the customer.
The technician can mark or update the request as complete, which helps the admin and customer get notified about the request completion.
Technicians receive a rating based on their request completion.
The technician can log out of the system


**ADMIN**

Admin logs in to the system
Admin views the dashboard, which represents a short overview of the system. It displays total requests, total assigned requests, total no. of technicians and users, etc.
Admin can view and delete the request.
Admin sends the notification to the user about the approval and rejection of the request via email.
Admin can assign the request to the technician. Admin can also update the technician assignment.
Admin can add the services related to mobile repairs that the project offers.
The work page only displays the completed requests.
The overall status page displays all the requests that are assigned, not assigned, and completed.
The Work Report page displays the request, particularly via the date filter.
Admin can do the CRUD process for all admins, technician and users.
Admin can view and update their details
Admin can logout of the system
