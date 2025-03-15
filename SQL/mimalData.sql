
INSERT INTO `inhala`.`days` (`dayName`) VALUES
('lunes'),
('martes'),
('miércoles'),
('jueves'),
('viernes'),
('sábado'),
('domingo');

INSERT INTO `inhala`.`fieldOfStudy` (`nameFieldStudy`, `description`) VALUES
('masoterapeuta', 'Especialista en masajes terapéuticos'),
('psicologo', 'Especialista en psicología'),
('fisioterapia', 'Especialista en fisioterapia'),
('cosmetologia', 'Especialista en cosmetología');


INSERT INTO `inhala`.`appointmentStatus` (`status`, `description`) VALUES
('nueva', 'Cita nueva'),
('pendiente', 'Cita pendiente'),
('confirmada', 'Cita confirmada'),
('finalizada', 'Cita finalizada'),
('a domicilio', 'Cita a domicilio');

INSERT INTO `inhala`.`roles` (`roleName`) VALUES
('Admin'),
('profesional'),
('member');


INSERT INTO `services` (
    `serviceName`, 
    `serviceDescription`, 
    `cost`, 
    `duration`, 
    `uniqueService`, 
    `fieldOfStudyID`
) VALUES 
('Masaje Relajante', 'Masaje suave para aliviar el estrés y la tensión', 50.00, '01:00:00', 0, 2),
('Masaje Descontracturante', 'Terapia focalizada para aliviar contracturas musculares', 60.00, '01:00:00', 0, 2),
('Masaje Deportivo', 'Masaje profundo para preparación y recuperación muscular', 70.00, '01:15:00', 0, 2),
('Masaje Linfático', 'Drenaje linfático manual para mejorar la circulación', 65.00, '01:00:00', 0, 2),
('Masaje Terapéutico', 'Masaje con técnicas especializadas para aliviar dolores específicos', 75.00, '01:30:00', 0, 2),
('Reflexología', 'Terapia en los pies para equilibrar el cuerpo y aliviar tensiones', 55.00, '00:45:00', 0, 2),
('Masaje con Piedras Calientes', 'Masaje relajante con piedras volcánicas para aliviar tensión', 80.00, '01:30:00', 0, 2),
('Masaje Prenatal', 'Terapia suave para aliviar molestias en embarazadas', 65.00, '01:00:00', 0, 2);

