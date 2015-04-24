SELECT 
    allotments.user_id
FROM
    allotments
        JOIN
    applications ON applications.user_id = allotments.user_id
WHERE
    applications.religion_id = 14 AND allotments.status ='confirmed';