SELECT 
user_id,
    COUNT(applications.status)
FROM
    applications
WHERE
    applications.religion_id NOT IN (SELECT 
            religion_id
        FROM
            religions
        WHERE
            reservation_percentage > 0)
	AND course_id = 1
        AND applications.status = 'confirmed'