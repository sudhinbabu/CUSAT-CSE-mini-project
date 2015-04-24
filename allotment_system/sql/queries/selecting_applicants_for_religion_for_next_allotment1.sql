SELECT 
    applications.user_id,
    marks.subject_name,
    applications.current_preference,
    (SUM(marks.awarded_mark) / sum(marks.max_mark) * 1200) AS index_mark
FROM
    applications
        JOIN
    marks ON applications.user_id = marks.user_id

WHERE    applications.religion_id = 14
    AND applications.chellan_payment = 1
AND applications.status!='confirmed'
GROUP BY applications.user_id
HAVING marks.subject_name = 'total_marks'
ORDER BY index_mark DESC
