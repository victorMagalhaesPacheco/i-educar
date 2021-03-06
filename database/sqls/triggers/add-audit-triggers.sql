CREATE TRIGGER trigger_audita_falta_componente_curricular AFTER INSERT OR DELETE OR UPDATE ON modules.falta_componente_curricular FOR EACH ROW EXECUTE PROCEDURE modules.audita_falta_componente_curricular();
CREATE TRIGGER trigger_audita_falta_geral AFTER INSERT OR DELETE OR UPDATE ON modules.falta_geral FOR EACH ROW EXECUTE PROCEDURE modules.audita_falta_geral();
CREATE TRIGGER trigger_audita_media_geral AFTER INSERT OR DELETE OR UPDATE ON modules.media_geral FOR EACH ROW EXECUTE PROCEDURE modules.audita_media_geral();
CREATE TRIGGER trigger_audita_nota_componente_curricular AFTER INSERT OR DELETE OR UPDATE ON modules.nota_componente_curricular FOR EACH ROW EXECUTE PROCEDURE modules.audita_nota_componente_curricular();
CREATE TRIGGER trigger_audita_nota_componente_curricular_media AFTER INSERT OR DELETE OR UPDATE ON modules.nota_componente_curricular_media FOR EACH ROW EXECUTE PROCEDURE modules.audita_nota_componente_curricular_media();
CREATE TRIGGER trigger_audita_nota_exame AFTER INSERT OR DELETE OR UPDATE ON modules.nota_exame FOR EACH ROW EXECUTE PROCEDURE modules.audita_nota_exame();
CREATE TRIGGER trigger_audita_nota_geral AFTER INSERT OR DELETE OR UPDATE ON modules.nota_geral FOR EACH ROW EXECUTE PROCEDURE modules.audita_nota_geral();
CREATE TRIGGER trigger_audita_parecer_componente_curricular AFTER INSERT OR DELETE OR UPDATE ON modules.parecer_componente_curricular FOR EACH ROW EXECUTE PROCEDURE modules.audita_parecer_componente_curricular();
CREATE TRIGGER trigger_audita_parecer_geral AFTER INSERT OR DELETE OR UPDATE ON modules.parecer_geral FOR EACH ROW EXECUTE PROCEDURE modules.audita_parecer_geral();
CREATE TRIGGER trigger_audita_matricula AFTER INSERT OR DELETE OR UPDATE ON pmieducar.matricula FOR EACH ROW EXECUTE PROCEDURE pmieducar.audita_matricula();
CREATE TRIGGER trigger_audita_matricula_turma AFTER INSERT OR DELETE OR UPDATE ON pmieducar.matricula_turma FOR EACH ROW EXECUTE PROCEDURE pmieducar.audita_matricula_turma();
