UPDATE applications SET alloted_course_id = NULL , status = NULL , allotment_number  = NULL , current_preference = 1;
UPDATE settings SET value = 0 WHERE label = 'SYSTEM_STATUS';

UPDATE settings 
SET 
    value = 'TRUE'
WHERE
    label = 'SUBMIT_APPLICATION';


-- UPDATE users SET password = "4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f11f963b582bef62449fae15717941d1ca6a12cfa6dc9bd3a04d59e6d78f98f941" 
