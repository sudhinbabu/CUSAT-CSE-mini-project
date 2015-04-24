SELECT 
    applications.user_id,
    marks.subject_name,
    applications.religion_id,
    applications.chellan_payment,
    applications.current_preference,
    (SUM(marks.awarded_mark) / sum(marks.max_mark) * 1200) AS index_mark
FROM
    applications
        JOIN
    marks ON applications.user_id = marks.user_id
WHERE applications.user_id NOT IN(SELECT user_id FROM applications WHERE applications.status IN('confirmed','alloted') )
GROUP BY applications.user_id
HAVING marks.subject_name = 'total_marks'
    AND applications.chellan_payment = 1
ORDER BY index_mark DESC