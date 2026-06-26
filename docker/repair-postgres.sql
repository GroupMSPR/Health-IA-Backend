\set ON_ERROR_STOP on

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'sail') THEN
        CREATE ROLE sail LOGIN PASSWORD 'password';
    ELSE
        ALTER ROLE sail WITH LOGIN PASSWORD 'password';
    END IF;
END
$$;

SELECT format('CREATE DATABASE %I OWNER %I', 'laravel', 'sail')
WHERE NOT EXISTS (SELECT 1 FROM pg_database WHERE datname = 'laravel')
\gexec

SELECT format('ALTER DATABASE %I OWNER TO %I', 'laravel', 'sail')
WHERE EXISTS (SELECT 1 FROM pg_database WHERE datname = 'laravel')
\gexec

\connect laravel
ALTER SCHEMA public OWNER TO sail;
GRANT ALL ON SCHEMA public TO sail;
GRANT ALL PRIVILEGES ON DATABASE laravel TO sail;