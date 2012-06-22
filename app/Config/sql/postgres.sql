
CREATE TABLE attachments (
    id integer NOT NULL,
    container_id integer DEFAULT 0 NOT NULL,
    container_type character varying(30) NOT NULL,
    filename character varying(255) NOT NULL,
    disk_filename character varying(255) NOT NULL,
    filesize integer DEFAULT 0 NOT NULL,
    content_type character varying(255) DEFAULT NULL::character varying,
    digest character varying(40) NOT NULL,
    downloads integer DEFAULT 0 NOT NULL,
    author_id integer DEFAULT 0 NOT NULL,
    created_on timestamp without time zone,
    description character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.attachments OWNER TO pgadmin;


CREATE SEQUENCE attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.attachments_id_seq OWNER TO pgadmin;

ALTER SEQUENCE attachments_id_seq OWNED BY attachments.id;

SELECT pg_catalog.setval('attachments_id_seq', 1, false);

CREATE TABLE auth_sources (
    id integer NOT NULL,
    type character varying(30) NOT NULL,
    name character varying(60) NOT NULL,
    host character varying(60) DEFAULT NULL::character varying,
    port integer,
    account character varying(255) DEFAULT NULL::character varying,
    account_password character varying(60) DEFAULT NULL::character varying,
    base_dn character varying(255) DEFAULT NULL::character varying,
    attr_login character varying(30) DEFAULT NULL::character varying,
    attr_firstname character varying(30) DEFAULT NULL::character varying,
    attr_lastname character varying(30) DEFAULT NULL::character varying,
    attr_mail character varying(30) DEFAULT NULL::character varying,
    onthefly_register boolean DEFAULT false NOT NULL,
    tls boolean DEFAULT false NOT NULL
);


ALTER TABLE public.auth_sources OWNER TO pgadmin;

CREATE SEQUENCE auth_sources_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.auth_sources_id_seq OWNER TO pgadmin;

ALTER SEQUENCE auth_sources_id_seq OWNED BY auth_sources.id;

SELECT pg_catalog.setval('auth_sources_id_seq', 1, false);

CREATE TABLE boards (
    id integer NOT NULL,
    project_id integer NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255) DEFAULT NULL::character varying,
    "position" integer DEFAULT 1,
    topics_count integer DEFAULT 0 NOT NULL,
    messages_count integer DEFAULT 0 NOT NULL,
    last_message_id integer
);


ALTER TABLE public.boards OWNER TO pgadmin;

CREATE SEQUENCE boards_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.boards_id_seq OWNER TO pgadmin;

ALTER SEQUENCE boards_id_seq OWNED BY boards.id;

SELECT pg_catalog.setval('boards_id_seq', 1, false);

CREATE TABLE changes (
    id integer NOT NULL,
    changeset_id integer NOT NULL,
    action character varying(1) NOT NULL,
    path character varying(255) NOT NULL,
    from_path character varying(255) DEFAULT NULL::character varying,
    from_revision character varying(255) DEFAULT NULL::character varying,
    revision character varying(255) DEFAULT NULL::character varying,
    branch character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.changes OWNER TO pgadmin;

CREATE SEQUENCE changes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.changes_id_seq OWNER TO pgadmin;

ALTER SEQUENCE changes_id_seq OWNED BY changes.id;

SELECT pg_catalog.setval('changes_id_seq', 1, false);

CREATE TABLE changesets (
    id integer NOT NULL,
    repository_id integer NOT NULL,
    revision character varying(255) NOT NULL,
    committer character varying(255) DEFAULT NULL::character varying,
    committed_on timestamp without time zone NOT NULL,
    comments text,
    commit_date date,
    scmid character varying(255) DEFAULT NULL::character varying,
    user_id integer
);


ALTER TABLE public.changesets OWNER TO pgadmin;

CREATE SEQUENCE changesets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.changesets_id_seq OWNER TO pgadmin;

ALTER SEQUENCE changesets_id_seq OWNED BY changesets.id;

SELECT pg_catalog.setval('changesets_id_seq', 1, false);

CREATE TABLE changesets_issues (
    changeset_id integer NOT NULL,
    issue_id integer NOT NULL
);


ALTER TABLE public.changesets_issues OWNER TO pgadmin;

CREATE SEQUENCE changesets_issues_changeset_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.changesets_issues_changeset_id_seq OWNER TO pgadmin;

ALTER SEQUENCE changesets_issues_changeset_id_seq OWNED BY changesets_issues.changeset_id;

SELECT pg_catalog.setval('changesets_issues_changeset_id_seq', 1, false);

CREATE SEQUENCE changesets_issues_issue_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.changesets_issues_issue_id_seq OWNER TO pgadmin;

ALTER SEQUENCE changesets_issues_issue_id_seq OWNED BY changesets_issues.issue_id;

SELECT pg_catalog.setval('changesets_issues_issue_id_seq', 1, false);

CREATE TABLE comments (
    id integer NOT NULL,
    commented_type character varying(30) NOT NULL,
    commented_id integer DEFAULT 0 NOT NULL,
    author_id integer DEFAULT 0 NOT NULL,
    comments text,
    created_on timestamp without time zone NOT NULL,
    updated_on timestamp without time zone NOT NULL
);


ALTER TABLE public.comments OWNER TO pgadmin;

CREATE SEQUENCE comments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comments_id_seq OWNER TO pgadmin;

ALTER SEQUENCE comments_id_seq OWNED BY comments.id;

SELECT pg_catalog.setval('comments_id_seq', 1, false);

CREATE TABLE custom_fields (
    id integer NOT NULL,
    type character varying(30) NOT NULL,
    name character varying(30) NOT NULL,
    field_format character varying(30) NOT NULL,
    possible_values text,
    regexp character varying(255) DEFAULT NULL::character varying,
    min_length integer DEFAULT 0 NOT NULL,
    max_length integer DEFAULT 0 NOT NULL,
    is_required boolean DEFAULT false NOT NULL,
    is_for_all boolean DEFAULT false NOT NULL,
    is_filter boolean DEFAULT false NOT NULL,
    "position" integer DEFAULT 1,
    searchable boolean DEFAULT false,
    default_value text
);


ALTER TABLE public.custom_fields OWNER TO pgadmin;

CREATE SEQUENCE custom_fields_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.custom_fields_id_seq OWNER TO pgadmin;

ALTER SEQUENCE custom_fields_id_seq OWNED BY custom_fields.id;

SELECT pg_catalog.setval('custom_fields_id_seq', 1, false);

CREATE TABLE custom_fields_projects (
    custom_field_id integer DEFAULT 0 NOT NULL,
    project_id integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.custom_fields_projects OWNER TO pgadmin;

CREATE TABLE custom_fields_trackers (
    custom_field_id integer DEFAULT 0 NOT NULL,
    tracker_id integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.custom_fields_trackers OWNER TO pgadmin;

CREATE TABLE custom_values (
    id integer NOT NULL,
    customized_type character varying(30) NOT NULL,
    customized_id integer DEFAULT 0 NOT NULL,
    custom_field_id integer DEFAULT 0 NOT NULL,
    value text
);


ALTER TABLE public.custom_values OWNER TO pgadmin;

CREATE SEQUENCE custom_values_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.custom_values_id_seq OWNER TO pgadmin;

ALTER SEQUENCE custom_values_id_seq OWNED BY custom_values.id;

SELECT pg_catalog.setval('custom_values_id_seq', 1, false);

CREATE TABLE documents (
    id integer NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    category_id integer DEFAULT 0 NOT NULL,
    title character varying(60) NOT NULL,
    description text,
    created_on timestamp without time zone
);


ALTER TABLE public.documents OWNER TO pgadmin;

CREATE SEQUENCE documents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.documents_id_seq OWNER TO pgadmin;

ALTER SEQUENCE documents_id_seq OWNED BY documents.id;

SELECT pg_catalog.setval('documents_id_seq', 1, false);

CREATE TABLE enabled_modules (
    id integer NOT NULL,
    project_id integer,
    name character varying(255) NOT NULL
);


ALTER TABLE public.enabled_modules OWNER TO pgadmin;

CREATE SEQUENCE enabled_modules_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.enabled_modules_id_seq OWNER TO pgadmin;

ALTER SEQUENCE enabled_modules_id_seq OWNED BY enabled_modules.id;

SELECT pg_catalog.setval('enabled_modules_id_seq', 9, false);

CREATE TABLE enumerations (
    id integer NOT NULL,
    opt character varying(4) NOT NULL,
    name character varying(30) NOT NULL,
    "position" integer DEFAULT 1,
    is_default boolean DEFAULT false NOT NULL
);


ALTER TABLE public.enumerations OWNER TO pgadmin;

CREATE SEQUENCE enumerations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.enumerations_id_seq OWNER TO pgadmin;

ALTER SEQUENCE enumerations_id_seq OWNED BY enumerations.id;

SELECT pg_catalog.setval('enumerations_id_seq', 10, false);

CREATE TABLE issue_categories (
    id integer NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    name character varying(30) NOT NULL,
    assigned_to_id integer
);


ALTER TABLE public.issue_categories OWNER TO pgadmin;

CREATE SEQUENCE issue_categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.issue_categories_id_seq OWNER TO pgadmin;

ALTER SEQUENCE issue_categories_id_seq OWNED BY issue_categories.id;

SELECT pg_catalog.setval('issue_categories_id_seq', 1, false);

CREATE TABLE issue_relations (
    id integer NOT NULL,
    issue_from_id integer NOT NULL,
    issue_to_id integer NOT NULL,
    relation_type character varying(255) NOT NULL,
    delay integer
);


ALTER TABLE public.issue_relations OWNER TO pgadmin;

CREATE SEQUENCE issue_relations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.issue_relations_id_seq OWNER TO pgadmin;

ALTER SEQUENCE issue_relations_id_seq OWNED BY issue_relations.id;

SELECT pg_catalog.setval('issue_relations_id_seq', 1, false);

CREATE TABLE issue_statuses (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    is_closed boolean DEFAULT false NOT NULL,
    is_default boolean DEFAULT false NOT NULL,
    "position" integer DEFAULT 1
);


ALTER TABLE public.issue_statuses OWNER TO pgadmin;

CREATE SEQUENCE issue_statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.issue_statuses_id_seq OWNER TO pgadmin;

ALTER SEQUENCE issue_statuses_id_seq OWNED BY issue_statuses.id;

SELECT pg_catalog.setval('issue_statuses_id_seq', 7, false);

CREATE TABLE issues (
    id integer NOT NULL,
    tracker_id integer DEFAULT 0 NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    subject character varying(255) NOT NULL,
    description text,
    due_date date,
    category_id integer,
    status_id integer DEFAULT 0 NOT NULL,
    assigned_to_id integer,
    priority_id integer DEFAULT 0 NOT NULL,
    fixed_version_id integer,
    author_id integer DEFAULT 0 NOT NULL,
    lock_version integer DEFAULT 0 NOT NULL,
    created_on timestamp without time zone,
    updated_on timestamp without time zone,
    start_date date,
    done_ratio integer DEFAULT 0 NOT NULL,
    estimated_hours double precision
);


ALTER TABLE public.issues OWNER TO pgadmin;

CREATE SEQUENCE issues_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.issues_id_seq OWNER TO pgadmin;

ALTER SEQUENCE issues_id_seq OWNED BY issues.id;

SELECT pg_catalog.setval('issues_id_seq', 4, false);

CREATE TABLE journal_details (
    id integer NOT NULL,
    journal_id integer DEFAULT 0 NOT NULL,
    property character varying(30) NOT NULL,
    prop_key character varying(30) NOT NULL,
    old_value character varying(255) DEFAULT NULL::character varying,
    value character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.journal_details OWNER TO pgadmin;

CREATE SEQUENCE journal_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.journal_details_id_seq OWNER TO pgadmin;

ALTER SEQUENCE journal_details_id_seq OWNED BY journal_details.id;

SELECT pg_catalog.setval('journal_details_id_seq', 1, false);

CREATE TABLE journals (
    id integer NOT NULL,
    journalized_id integer DEFAULT 0 NOT NULL,
    journalized_type character varying(30) NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    notes text,
    created_on timestamp without time zone NOT NULL
);


ALTER TABLE public.journals OWNER TO pgadmin;

CREATE SEQUENCE journals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.journals_id_seq OWNER TO pgadmin;

ALTER SEQUENCE journals_id_seq OWNED BY journals.id;

SELECT pg_catalog.setval('journals_id_seq', 1, false);

CREATE TABLE members (
    id integer NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    role_id integer DEFAULT 0 NOT NULL,
    created_on timestamp without time zone,
    mail_notification boolean DEFAULT false NOT NULL
);


ALTER TABLE public.members OWNER TO pgadmin;

CREATE SEQUENCE members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.members_id_seq OWNER TO pgadmin;

ALTER SEQUENCE members_id_seq OWNED BY members.id;

SELECT pg_catalog.setval('members_id_seq', 1, false);

CREATE TABLE news (
    id integer NOT NULL,
    project_id integer,
    title character varying(60) NOT NULL,
    summary character varying(255) DEFAULT NULL::character varying,
    description text,
    author_id integer DEFAULT 0 NOT NULL,
    created_on timestamp without time zone,
    comments_count integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.news OWNER TO pgadmin;

CREATE SEQUENCE news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.news_id_seq OWNER TO pgadmin;

ALTER SEQUENCE news_id_seq OWNED BY news.id;

SELECT pg_catalog.setval('news_id_seq', 3, false);

CREATE TABLE projects (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    description text,
    homepage character varying(255) DEFAULT NULL::character varying,
    is_public boolean DEFAULT true NOT NULL,
    parent_id integer,
    projects_count integer DEFAULT 0,
    created_on timestamp without time zone,
    updated_on timestamp without time zone,
    identifier character varying(20) DEFAULT NULL::character varying,
    status integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.projects OWNER TO pgadmin;

CREATE SEQUENCE projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.projects_id_seq OWNER TO pgadmin;

ALTER SEQUENCE projects_id_seq OWNED BY projects.id;

SELECT pg_catalog.setval('projects_id_seq', 2, false);

CREATE TABLE projects_trackers (
    project_id integer NOT NULL,
    tracker_id integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.projects_trackers OWNER TO pgadmin;

CREATE SEQUENCE projects_trackers_project_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.projects_trackers_project_id_seq OWNER TO pgadmin;

ALTER SEQUENCE projects_trackers_project_id_seq OWNED BY projects_trackers.project_id;

SELECT pg_catalog.setval('projects_trackers_project_id_seq', 1, false);

CREATE TABLE queries (
    id integer NOT NULL,
    project_id integer,
    name character varying(255) NOT NULL,
    filters text,
    user_id integer DEFAULT 0 NOT NULL,
    is_public boolean DEFAULT false NOT NULL,
    column_names text
);


ALTER TABLE public.queries OWNER TO pgadmin;

CREATE SEQUENCE queries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.queries_id_seq OWNER TO pgadmin;

ALTER SEQUENCE queries_id_seq OWNED BY queries.id;

SELECT pg_catalog.setval('queries_id_seq', 1, false);

CREATE TABLE repositories (
    id integer NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    url character varying(255) NOT NULL,
    login character varying(60) DEFAULT NULL::character varying,
    password character varying(60) DEFAULT NULL::character varying,
    root_url character varying(255) DEFAULT NULL::character varying,
    type character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.repositories OWNER TO pgadmin;

CREATE SEQUENCE repositories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.repositories_id_seq OWNER TO pgadmin;

ALTER SEQUENCE repositories_id_seq OWNED BY repositories.id;

SELECT pg_catalog.setval('repositories_id_seq', 1, false);

CREATE TABLE roles (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    "position" integer DEFAULT 1,
    assignable boolean DEFAULT true,
    builtin integer DEFAULT 0 NOT NULL,
    permissions text
);


ALTER TABLE public.roles OWNER TO pgadmin;

CREATE SEQUENCE roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.roles_id_seq OWNER TO pgadmin;

ALTER SEQUENCE roles_id_seq OWNED BY roles.id;

SELECT pg_catalog.setval('roles_id_seq', 6, false);

CREATE TABLE schema_migrations (
    version character varying(255) NOT NULL
);


ALTER TABLE public.schema_migrations OWNER TO pgadmin;

CREATE TABLE settings (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    value text,
    updated_on timestamp without time zone
);


ALTER TABLE public.settings OWNER TO pgadmin;

CREATE SEQUENCE settings_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.settings_id_seq OWNER TO pgadmin;

ALTER SEQUENCE settings_id_seq OWNED BY settings.id;

SELECT pg_catalog.setval('settings_id_seq', 1, false);

CREATE TABLE time_entries (
    id integer NOT NULL,
    project_id integer NOT NULL,
    user_id integer NOT NULL,
    issue_id integer,
    hours double precision NOT NULL,
    comments character varying(255) DEFAULT NULL::character varying,
    activity_id integer NOT NULL,
    spent_on date NOT NULL,
    tyear integer NOT NULL,
    tmonth integer NOT NULL,
    tweek integer NOT NULL,
    created_on timestamp without time zone NOT NULL,
    updated_on timestamp without time zone NOT NULL
);


ALTER TABLE public.time_entries OWNER TO pgadmin;

CREATE SEQUENCE time_entries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.time_entries_id_seq OWNER TO pgadmin;

ALTER SEQUENCE time_entries_id_seq OWNED BY time_entries.id;

SELECT pg_catalog.setval('time_entries_id_seq', 1, false);

CREATE TABLE tokens (
    id integer NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    action character varying(30) NOT NULL,
    value character varying(40) NOT NULL,
    created_on timestamp without time zone NOT NULL
);


ALTER TABLE public.tokens OWNER TO pgadmin;

CREATE SEQUENCE tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tokens_id_seq OWNER TO pgadmin;

ALTER SEQUENCE tokens_id_seq OWNED BY tokens.id;

SELECT pg_catalog.setval('tokens_id_seq', 5, false);

CREATE TABLE trackers (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    is_in_chlog boolean DEFAULT false NOT NULL,
    "position" integer DEFAULT 1,
    is_in_roadmap boolean DEFAULT true NOT NULL
);


ALTER TABLE public.trackers OWNER TO pgadmin;

CREATE SEQUENCE trackers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.trackers_id_seq OWNER TO pgadmin;

ALTER SEQUENCE trackers_id_seq OWNED BY trackers.id;

SELECT pg_catalog.setval('trackers_id_seq', 4, false);

CREATE TABLE user_preferences (
    id integer NOT NULL,
    user_id integer DEFAULT 0 NOT NULL,
    others text,
    hide_mail boolean DEFAULT false,
    time_zone character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.user_preferences OWNER TO pgadmin;

CREATE SEQUENCE user_preferences_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_preferences_id_seq OWNER TO pgadmin;

ALTER SEQUENCE user_preferences_id_seq OWNED BY user_preferences.id;

SELECT pg_catalog.setval('user_preferences_id_seq', 10, false);

CREATE TABLE users (
    id integer NOT NULL,
    login character varying(30) NOT NULL,
    hashed_password character varying(40) NOT NULL,
    firstname character varying(30) NOT NULL,
    lastname character varying(30) NOT NULL,
    mail character varying(60) NOT NULL,
    mail_notification boolean DEFAULT true NOT NULL,
    admin boolean DEFAULT false NOT NULL,
    status integer DEFAULT 1 NOT NULL,
    last_login_on timestamp without time zone,
    language character varying(5) DEFAULT NULL::character varying,
    auth_source_id integer,
    created_on timestamp without time zone,
    updated_on timestamp without time zone,
    type character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.users OWNER TO pgadmin;

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO pgadmin;

ALTER SEQUENCE users_id_seq OWNED BY users.id;

SELECT pg_catalog.setval('users_id_seq', 4, false);

CREATE TABLE versions (
    id integer NOT NULL,
    project_id integer DEFAULT 0 NOT NULL,
    name character varying(255) NOT NULL,
    description character varying(255) DEFAULT NULL::character varying,
    effective_date date,
    created_on timestamp without time zone,
    updated_on timestamp without time zone,
    wiki_page_title character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.versions OWNER TO pgadmin;

CREATE SEQUENCE versions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.versions_id_seq OWNER TO pgadmin;

ALTER SEQUENCE versions_id_seq OWNED BY versions.id;

SELECT pg_catalog.setval('versions_id_seq', 1, false);

CREATE TABLE watchers (
    id integer NOT NULL,
    watchable_type character varying(255) NOT NULL,
    watchable_id integer DEFAULT 0 NOT NULL,
    user_id integer
);


ALTER TABLE public.watchers OWNER TO pgadmin;

CREATE SEQUENCE watchers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.watchers_id_seq OWNER TO pgadmin;

ALTER SEQUENCE watchers_id_seq OWNED BY watchers.id;

SELECT pg_catalog.setval('watchers_id_seq', 1, false);

CREATE TABLE wiki_content_versions (
    id integer NOT NULL,
    wiki_content_id integer NOT NULL,
    page_id integer NOT NULL,
    author_id integer,
    data bytea,
    compression character varying(6) DEFAULT NULL::character varying,
    comments character varying(255) DEFAULT NULL::character varying,
    updated_on timestamp without time zone NOT NULL,
    version integer NOT NULL
);


ALTER TABLE public.wiki_content_versions OWNER TO pgadmin;

CREATE SEQUENCE wiki_content_versions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wiki_content_versions_id_seq OWNER TO pgadmin;

ALTER SEQUENCE wiki_content_versions_id_seq OWNED BY wiki_content_versions.id;

SELECT pg_catalog.setval('wiki_content_versions_id_seq', 4, false);

CREATE TABLE wiki_contents (
    id integer NOT NULL,
    page_id integer NOT NULL,
    author_id integer,
    text text,
    comments character varying(255) DEFAULT NULL::character varying,
    updated_on timestamp without time zone NOT NULL,
    version integer NOT NULL
);


ALTER TABLE public.wiki_contents OWNER TO pgadmin;

CREATE SEQUENCE wiki_contents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wiki_contents_id_seq OWNER TO pgadmin;

ALTER SEQUENCE wiki_contents_id_seq OWNED BY wiki_contents.id;

SELECT pg_catalog.setval('wiki_contents_id_seq', 3, false);

CREATE TABLE wiki_pages (
    id integer NOT NULL,
    wiki_id integer NOT NULL,
    title character varying(255) NOT NULL,
    created_on timestamp without time zone NOT NULL,
    protected boolean DEFAULT false NOT NULL,
    parent_id integer
);


ALTER TABLE public.wiki_pages OWNER TO pgadmin;

CREATE SEQUENCE wiki_pages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wiki_pages_id_seq OWNER TO pgadmin;

ALTER SEQUENCE wiki_pages_id_seq OWNED BY wiki_pages.id;

SELECT pg_catalog.setval('wiki_pages_id_seq', 3, false);

CREATE TABLE wiki_redirects (
    id integer NOT NULL,
    wiki_id integer NOT NULL,
    title character varying(255) DEFAULT NULL::character varying,
    redirects_to character varying(255) DEFAULT NULL::character varying,
    created_on timestamp without time zone NOT NULL
);


ALTER TABLE public.wiki_redirects OWNER TO pgadmin;

CREATE SEQUENCE wiki_redirects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wiki_redirects_id_seq OWNER TO pgadmin;

ALTER SEQUENCE wiki_redirects_id_seq OWNED BY wiki_redirects.id;

SELECT pg_catalog.setval('wiki_redirects_id_seq', 1, false);

CREATE TABLE wikis (
    id integer NOT NULL,
    project_id integer NOT NULL,
    start_page character varying(255) NOT NULL,
    status integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.wikis OWNER TO pgadmin;

CREATE SEQUENCE wikis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wikis_id_seq OWNER TO pgadmin;

ALTER SEQUENCE wikis_id_seq OWNED BY wikis.id;

SELECT pg_catalog.setval('wikis_id_seq', 2, false);

CREATE TABLE workflows (
    id integer NOT NULL,
    tracker_id integer DEFAULT 0 NOT NULL,
    old_status_id integer DEFAULT 0 NOT NULL,
    new_status_id integer DEFAULT 0 NOT NULL,
    role_id integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.workflows OWNER TO pgadmin;

CREATE SEQUENCE workflows_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.workflows_id_seq OWNER TO pgadmin;

ALTER SEQUENCE workflows_id_seq OWNED BY workflows.id;

SELECT pg_catalog.setval('workflows_id_seq', 145, false);

ALTER TABLE attachments ALTER COLUMN id SET DEFAULT nextval('attachments_id_seq'::regclass);

ALTER TABLE auth_sources ALTER COLUMN id SET DEFAULT nextval('auth_sources_id_seq'::regclass);

ALTER TABLE boards ALTER COLUMN id SET DEFAULT nextval('boards_id_seq'::regclass);

ALTER TABLE changes ALTER COLUMN id SET DEFAULT nextval('changes_id_seq'::regclass);

ALTER TABLE changesets ALTER COLUMN id SET DEFAULT nextval('changesets_id_seq'::regclass);

ALTER TABLE changesets_issues ALTER COLUMN changeset_id SET DEFAULT nextval('changesets_issues_changeset_id_seq'::regclass);

ALTER TABLE changesets_issues ALTER COLUMN issue_id SET DEFAULT nextval('changesets_issues_issue_id_seq'::regclass);

ALTER TABLE comments ALTER COLUMN id SET DEFAULT nextval('comments_id_seq'::regclass);

ALTER TABLE custom_fields ALTER COLUMN id SET DEFAULT nextval('custom_fields_id_seq'::regclass);

ALTER TABLE custom_values ALTER COLUMN id SET DEFAULT nextval('custom_values_id_seq'::regclass);

ALTER TABLE documents ALTER COLUMN id SET DEFAULT nextval('documents_id_seq'::regclass);

ALTER TABLE enabled_modules ALTER COLUMN id SET DEFAULT nextval('enabled_modules_id_seq'::regclass);

ALTER TABLE enumerations ALTER COLUMN id SET DEFAULT nextval('enumerations_id_seq'::regclass);

ALTER TABLE issue_categories ALTER COLUMN id SET DEFAULT nextval('issue_categories_id_seq'::regclass);

ALTER TABLE issue_relations ALTER COLUMN id SET DEFAULT nextval('issue_relations_id_seq'::regclass);

ALTER TABLE issue_statuses ALTER COLUMN id SET DEFAULT nextval('issue_statuses_id_seq'::regclass);

ALTER TABLE issues ALTER COLUMN id SET DEFAULT nextval('issues_id_seq'::regclass);

ALTER TABLE journal_details ALTER COLUMN id SET DEFAULT nextval('journal_details_id_seq'::regclass);

ALTER TABLE journals ALTER COLUMN id SET DEFAULT nextval('journals_id_seq'::regclass);

ALTER TABLE members ALTER COLUMN id SET DEFAULT nextval('members_id_seq'::regclass);

ALTER TABLE news ALTER COLUMN id SET DEFAULT nextval('news_id_seq'::regclass);

ALTER TABLE projects ALTER COLUMN id SET DEFAULT nextval('projects_id_seq'::regclass);

ALTER TABLE projects_trackers ALTER COLUMN project_id SET DEFAULT nextval('projects_trackers_project_id_seq'::regclass);

ALTER TABLE queries ALTER COLUMN id SET DEFAULT nextval('queries_id_seq'::regclass);

ALTER TABLE repositories ALTER COLUMN id SET DEFAULT nextval('repositories_id_seq'::regclass);

ALTER TABLE roles ALTER COLUMN id SET DEFAULT nextval('roles_id_seq'::regclass);

ALTER TABLE settings ALTER COLUMN id SET DEFAULT nextval('settings_id_seq'::regclass);

ALTER TABLE time_entries ALTER COLUMN id SET DEFAULT nextval('time_entries_id_seq'::regclass);

ALTER TABLE tokens ALTER COLUMN id SET DEFAULT nextval('tokens_id_seq'::regclass);

ALTER TABLE trackers ALTER COLUMN id SET DEFAULT nextval('trackers_id_seq'::regclass);

ALTER TABLE user_preferences ALTER COLUMN id SET DEFAULT nextval('user_preferences_id_seq'::regclass);

ALTER TABLE users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);

ALTER TABLE versions ALTER COLUMN id SET DEFAULT nextval('versions_id_seq'::regclass);

ALTER TABLE watchers ALTER COLUMN id SET DEFAULT nextval('watchers_id_seq'::regclass);

ALTER TABLE wiki_content_versions ALTER COLUMN id SET DEFAULT nextval('wiki_content_versions_id_seq'::regclass);

ALTER TABLE wiki_contents ALTER COLUMN id SET DEFAULT nextval('wiki_contents_id_seq'::regclass);

ALTER TABLE wiki_pages ALTER COLUMN id SET DEFAULT nextval('wiki_pages_id_seq'::regclass);

ALTER TABLE wiki_redirects ALTER COLUMN id SET DEFAULT nextval('wiki_redirects_id_seq'::regclass);

ALTER TABLE wikis ALTER COLUMN id SET DEFAULT nextval('wikis_id_seq'::regclass);

ALTER TABLE workflows ALTER COLUMN id SET DEFAULT nextval('workflows_id_seq'::regclass);

INSERT INTO enabled_modules VALUES (1, 1, 'issue_tracking');
INSERT INTO enabled_modules VALUES (2, 1, 'time_tracking');
INSERT INTO enabled_modules VALUES (3, 1, 'news');
INSERT INTO enabled_modules VALUES (4, 1, 'documents');
INSERT INTO enabled_modules VALUES (5, 1, 'files');
INSERT INTO enabled_modules VALUES (6, 1, 'wiki');
INSERT INTO enabled_modules VALUES (7, 1, 'repository');
INSERT INTO enabled_modules VALUES (8, 1, 'boards');

INSERT INTO enumerations VALUES (1, 'DCAT', 'User documentation', 1, false);
INSERT INTO enumerations VALUES (2, 'DCAT', 'Technical documentation', 2, false);
INSERT INTO enumerations VALUES (3, 'IPRI', 'Low', 1, false);
INSERT INTO enumerations VALUES (4, 'IPRI', 'Normal', 2, true);
INSERT INTO enumerations VALUES (5, 'IPRI', 'High', 3, false);
INSERT INTO enumerations VALUES (6, 'IPRI', 'Urgent', 4, false);
INSERT INTO enumerations VALUES (7, 'IPRI', 'Immediate', 5, false);
INSERT INTO enumerations VALUES (8, 'ACTI', 'Design', 1, false);
INSERT INTO enumerations VALUES (9, 'ACTI', 'Development', 2, false);

INSERT INTO issue_statuses VALUES (1, 'New', false, true, 1);
INSERT INTO issue_statuses VALUES (2, 'Assigned', false, true, 2);
INSERT INTO issue_statuses VALUES (3, 'Resolved', false, true, 3);
INSERT INTO issue_statuses VALUES (4, 'Feedback', false, true, 4);
INSERT INTO issue_statuses VALUES (5, 'Closed', true, true, 5);
INSERT INTO issue_statuses VALUES (6, 'Rejected', true, true, 6);

INSERT INTO issues VALUES (1, 1, 1, 'Sample Ticket', 'Hello candycane users.', NULL, NULL, 1, NULL, 4, NULL, 3, 0, '2009-03-14 10:32:00', '2009-03-14 10:32:00', '2009-03-14', 0, NULL);


INSERT INTO news VALUES (1, 1, 'Sample News', 'Working fine.', 'Worked
*YEAH!!*', 3, '2009-03-20 23:25:45', 0);

INSERT INTO projects VALUES (1, 'Sample Project', 'Candycane rocks!', '', true, NULL, 0, '2009-03-04 23:09:49', '2009-03-04 23:09:49', 'sampleproject', 1);

INSERT INTO projects_trackers VALUES (1, 1);
INSERT INTO projects_trackers VALUES (1, 2);
INSERT INTO projects_trackers VALUES (1, 3);


INSERT INTO roles VALUES (1, 'Non member', 1, true, 1, '--- 
- :add_issues
- :add_issue_notes
- :save_queries
- :view_gantt
- :view_calendar
- :view_time_entries
- :comment_news
- :view_documents
- :view_wiki_pages
- :view_wiki_edits
- :add_messages
- :view_files
- :browse_repository
- :view_changesets
');
INSERT INTO roles VALUES (2, 'Anonymous', 2, true, 2, '--- 
- :view_gantt
- :view_calendar
- :view_time_entries
- :view_documents
- :view_wiki_pages
- :view_wiki_edits
- :view_files
- :browse_repository
- :view_changesets
');
INSERT INTO roles VALUES (3, 'Manager', 3, true, 0, '--- 
- :edit_project
- :select_project_modules
- :manage_members
- :manage_versions
- :manage_categories
- :add_issues
- :edit_issues
- :manage_issue_relations
- :add_issue_notes
- :edit_issue_notes
- :edit_own_issue_notes
- :move_issues
- :delete_issues
- :manage_public_queries
- :save_queries
- :view_gantt
- :view_calendar
- :view_issue_watchers
- :add_issue_watchers
- :log_time
- :view_time_entries
- :edit_time_entries
- :edit_own_time_entries
- :manage_news
- :comment_news
- :manage_documents
- :view_documents
- :manage_files
- :view_files
- :manage_wiki
- :rename_wiki_pages
- :delete_wiki_pages
- :view_wiki_pages
- :view_wiki_edits
- :edit_wiki_pages
- :delete_wiki_pages_attachments
- :protect_wiki_pages
- :manage_repository
- :browse_repository
- :view_changesets
- :commit_access
- :manage_boards
- :add_messages
- :edit_messages
- :edit_own_messages
- :delete_messages
- :delete_own_messages
');
INSERT INTO roles VALUES (4, 'Developer', 4, true, 0, '--- 
- :manage_versions
- :manage_categories
- :add_issues
- :edit_issues
- :manage_issue_relations
- :add_issue_notes
- :save_queries
- :view_gantt
- :view_calendar
- :log_time
- :view_time_entries
- :comment_news
- :view_documents
- :view_wiki_pages
- :view_wiki_edits
- :edit_wiki_pages
- :delete_wiki_pages
- :add_messages
- :edit_own_messages
- :view_files
- :manage_files
- :browse_repository
- :view_changesets
- :commit_access
');
INSERT INTO roles VALUES (5, 'Reporter', 5, true, 0, '--- 
- :add_issues
- :add_issue_notes
- :save_queries
- :view_gantt
- :view_calendar
- :log_time
- :view_time_entries
- :comment_news
- :view_documents
- :view_wiki_pages
- :view_wiki_edits
- :add_messages
- :edit_own_messages
- :view_files
- :browse_repository
- :view_changesets
');

INSERT INTO schema_migrations VALUES ('1');
INSERT INTO schema_migrations VALUES ('10');
INSERT INTO schema_migrations VALUES ('100');
INSERT INTO schema_migrations VALUES ('101');
INSERT INTO schema_migrations VALUES ('11');
INSERT INTO schema_migrations VALUES ('12');
INSERT INTO schema_migrations VALUES ('13');
INSERT INTO schema_migrations VALUES ('14');
INSERT INTO schema_migrations VALUES ('15');
INSERT INTO schema_migrations VALUES ('16');
INSERT INTO schema_migrations VALUES ('17');
INSERT INTO schema_migrations VALUES ('18');
INSERT INTO schema_migrations VALUES ('19');
INSERT INTO schema_migrations VALUES ('2');
INSERT INTO schema_migrations VALUES ('20');
INSERT INTO schema_migrations VALUES ('21');
INSERT INTO schema_migrations VALUES ('22');
INSERT INTO schema_migrations VALUES ('23');
INSERT INTO schema_migrations VALUES ('24');
INSERT INTO schema_migrations VALUES ('25');
INSERT INTO schema_migrations VALUES ('26');
INSERT INTO schema_migrations VALUES ('27');
INSERT INTO schema_migrations VALUES ('28');
INSERT INTO schema_migrations VALUES ('29');
INSERT INTO schema_migrations VALUES ('3');
INSERT INTO schema_migrations VALUES ('30');
INSERT INTO schema_migrations VALUES ('31');
INSERT INTO schema_migrations VALUES ('32');
INSERT INTO schema_migrations VALUES ('33');
INSERT INTO schema_migrations VALUES ('34');
INSERT INTO schema_migrations VALUES ('35');
INSERT INTO schema_migrations VALUES ('36');
INSERT INTO schema_migrations VALUES ('37');
INSERT INTO schema_migrations VALUES ('38');
INSERT INTO schema_migrations VALUES ('39');
INSERT INTO schema_migrations VALUES ('4');
INSERT INTO schema_migrations VALUES ('40');
INSERT INTO schema_migrations VALUES ('41');
INSERT INTO schema_migrations VALUES ('42');
INSERT INTO schema_migrations VALUES ('43');
INSERT INTO schema_migrations VALUES ('44');
INSERT INTO schema_migrations VALUES ('45');
INSERT INTO schema_migrations VALUES ('46');
INSERT INTO schema_migrations VALUES ('47');
INSERT INTO schema_migrations VALUES ('48');
INSERT INTO schema_migrations VALUES ('49');
INSERT INTO schema_migrations VALUES ('5');
INSERT INTO schema_migrations VALUES ('50');
INSERT INTO schema_migrations VALUES ('51');
INSERT INTO schema_migrations VALUES ('52');
INSERT INTO schema_migrations VALUES ('53');
INSERT INTO schema_migrations VALUES ('54');
INSERT INTO schema_migrations VALUES ('55');
INSERT INTO schema_migrations VALUES ('56');
INSERT INTO schema_migrations VALUES ('57');
INSERT INTO schema_migrations VALUES ('58');
INSERT INTO schema_migrations VALUES ('59');
INSERT INTO schema_migrations VALUES ('6');
INSERT INTO schema_migrations VALUES ('60');
INSERT INTO schema_migrations VALUES ('61');
INSERT INTO schema_migrations VALUES ('62');
INSERT INTO schema_migrations VALUES ('63');
INSERT INTO schema_migrations VALUES ('64');
INSERT INTO schema_migrations VALUES ('65');
INSERT INTO schema_migrations VALUES ('66');
INSERT INTO schema_migrations VALUES ('67');
INSERT INTO schema_migrations VALUES ('68');
INSERT INTO schema_migrations VALUES ('69');
INSERT INTO schema_migrations VALUES ('7');
INSERT INTO schema_migrations VALUES ('70');
INSERT INTO schema_migrations VALUES ('71');
INSERT INTO schema_migrations VALUES ('72');
INSERT INTO schema_migrations VALUES ('73');
INSERT INTO schema_migrations VALUES ('74');
INSERT INTO schema_migrations VALUES ('75');
INSERT INTO schema_migrations VALUES ('76');
INSERT INTO schema_migrations VALUES ('77');
INSERT INTO schema_migrations VALUES ('78');
INSERT INTO schema_migrations VALUES ('79');
INSERT INTO schema_migrations VALUES ('8');
INSERT INTO schema_migrations VALUES ('80');
INSERT INTO schema_migrations VALUES ('81');
INSERT INTO schema_migrations VALUES ('82');
INSERT INTO schema_migrations VALUES ('83');
INSERT INTO schema_migrations VALUES ('84');
INSERT INTO schema_migrations VALUES ('85');
INSERT INTO schema_migrations VALUES ('86');
INSERT INTO schema_migrations VALUES ('87');
INSERT INTO schema_migrations VALUES ('88');
INSERT INTO schema_migrations VALUES ('89');
INSERT INTO schema_migrations VALUES ('9');
INSERT INTO schema_migrations VALUES ('90');
INSERT INTO schema_migrations VALUES ('91');
INSERT INTO schema_migrations VALUES ('92');
INSERT INTO schema_migrations VALUES ('93');
INSERT INTO schema_migrations VALUES ('94');
INSERT INTO schema_migrations VALUES ('95');
INSERT INTO schema_migrations VALUES ('96');
INSERT INTO schema_migrations VALUES ('97');
INSERT INTO schema_migrations VALUES ('98');
INSERT INTO schema_migrations VALUES ('99');


INSERT INTO tokens VALUES (1, 1, 'feeds', 'D7ukdhHJXK7MTwDELqToVcTHPczo4rbCsLTim0pv', '2009-03-04 23:03:11');
INSERT INTO tokens VALUES (2, 1, 'feeds', 'rV5I24UQkA7AImh0FOYM84eNSpDbsOpTFCRcMort', '2009-03-04 23:03:11');
INSERT INTO tokens VALUES (3, 3, 'feeds', 'Zi1s5C1vyA8TAzMXm2hAAIOD8CveWiT3LSI763Ie', '2009-03-04 23:08:46');
INSERT INTO tokens VALUES (4, 3, 'feeds', 'HxAUNOsdgv1y3m8Y0ilEOpW6P3sQaydgCxcmsHx8', '2009-03-04 23:08:46');


INSERT INTO trackers VALUES (1, 'Bug', true, 1, false);
INSERT INTO trackers VALUES (2, 'Feature', true, 2, true);
INSERT INTO trackers VALUES (3, 'Support', false, 3, false);

INSERT INTO user_preferences VALUES (1, 1, '--- {}

', false, NULL);
INSERT INTO user_preferences VALUES (2, 2, '--- {}

', false, NULL);



INSERT INTO users VALUES (1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Redmine', 'Admin', 'admin@example.net', true, true, 1, '2009-03-04 23:06:50', 'eng', NULL, '2009-03-04 23:00:57', '2009-03-04 23:06:50', 'User');
INSERT INTO users VALUES (2, '', '', '', 'Anonymous', '', false, false, 0, NULL, 'eng', NULL, '2009-03-04 23:02:30', '2009-03-04 23:02:30', 'AnonymousUser');
INSERT INTO users VALUES (3, 'testuser', 'AWESOME', '', 'Anonymous', 'test@example.com', false, false, 0, NULL, 'eng', NULL, '2009-03-04 23:02:30', '2009-03-04 23:02:30', 'TestUser');

INSERT INTO workflows VALUES (1, 1, 1, 2, 3);
INSERT INTO workflows VALUES (2, 1, 1, 3, 3);
INSERT INTO workflows VALUES (3, 1, 1, 4, 3);
INSERT INTO workflows VALUES (4, 1, 1, 5, 3);
INSERT INTO workflows VALUES (5, 1, 1, 6, 3);
INSERT INTO workflows VALUES (6, 1, 2, 1, 3);
INSERT INTO workflows VALUES (7, 1, 2, 3, 3);
INSERT INTO workflows VALUES (8, 1, 2, 4, 3);
INSERT INTO workflows VALUES (9, 1, 2, 5, 3);
INSERT INTO workflows VALUES (10, 1, 2, 6, 3);
INSERT INTO workflows VALUES (11, 1, 3, 1, 3);
INSERT INTO workflows VALUES (12, 1, 3, 2, 3);
INSERT INTO workflows VALUES (13, 1, 3, 4, 3);
INSERT INTO workflows VALUES (14, 1, 3, 5, 3);
INSERT INTO workflows VALUES (15, 1, 3, 6, 3);
INSERT INTO workflows VALUES (16, 1, 4, 1, 3);
INSERT INTO workflows VALUES (17, 1, 4, 2, 3);
INSERT INTO workflows VALUES (18, 1, 4, 3, 3);
INSERT INTO workflows VALUES (19, 1, 4, 5, 3);
INSERT INTO workflows VALUES (20, 1, 4, 6, 3);
INSERT INTO workflows VALUES (21, 1, 5, 1, 3);
INSERT INTO workflows VALUES (22, 1, 5, 2, 3);
INSERT INTO workflows VALUES (23, 1, 5, 3, 3);
INSERT INTO workflows VALUES (24, 1, 5, 4, 3);
INSERT INTO workflows VALUES (25, 1, 5, 6, 3);
INSERT INTO workflows VALUES (26, 1, 6, 1, 3);
INSERT INTO workflows VALUES (27, 1, 6, 2, 3);
INSERT INTO workflows VALUES (28, 1, 6, 3, 3);
INSERT INTO workflows VALUES (29, 1, 6, 4, 3);
INSERT INTO workflows VALUES (30, 1, 6, 5, 3);
INSERT INTO workflows VALUES (31, 2, 1, 2, 3);
INSERT INTO workflows VALUES (32, 2, 1, 3, 3);
INSERT INTO workflows VALUES (33, 2, 1, 4, 3);
INSERT INTO workflows VALUES (34, 2, 1, 5, 3);
INSERT INTO workflows VALUES (35, 2, 1, 6, 3);
INSERT INTO workflows VALUES (36, 2, 2, 1, 3);
INSERT INTO workflows VALUES (37, 2, 2, 3, 3);
INSERT INTO workflows VALUES (38, 2, 2, 4, 3);
INSERT INTO workflows VALUES (39, 2, 2, 5, 3);
INSERT INTO workflows VALUES (40, 2, 2, 6, 3);
INSERT INTO workflows VALUES (41, 2, 3, 1, 3);
INSERT INTO workflows VALUES (42, 2, 3, 2, 3);
INSERT INTO workflows VALUES (43, 2, 3, 4, 3);
INSERT INTO workflows VALUES (44, 2, 3, 5, 3);
INSERT INTO workflows VALUES (45, 2, 3, 6, 3);
INSERT INTO workflows VALUES (46, 2, 4, 1, 3);
INSERT INTO workflows VALUES (47, 2, 4, 2, 3);
INSERT INTO workflows VALUES (48, 2, 4, 3, 3);
INSERT INTO workflows VALUES (49, 2, 4, 5, 3);
INSERT INTO workflows VALUES (50, 2, 4, 6, 3);
INSERT INTO workflows VALUES (51, 2, 5, 1, 3);
INSERT INTO workflows VALUES (52, 2, 5, 2, 3);
INSERT INTO workflows VALUES (53, 2, 5, 3, 3);
INSERT INTO workflows VALUES (54, 2, 5, 4, 3);
INSERT INTO workflows VALUES (55, 2, 5, 6, 3);
INSERT INTO workflows VALUES (56, 2, 6, 1, 3);
INSERT INTO workflows VALUES (57, 2, 6, 2, 3);
INSERT INTO workflows VALUES (58, 2, 6, 3, 3);
INSERT INTO workflows VALUES (59, 2, 6, 4, 3);
INSERT INTO workflows VALUES (60, 2, 6, 5, 3);
INSERT INTO workflows VALUES (61, 3, 1, 2, 3);
INSERT INTO workflows VALUES (62, 3, 1, 3, 3);
INSERT INTO workflows VALUES (63, 3, 1, 4, 3);
INSERT INTO workflows VALUES (64, 3, 1, 5, 3);
INSERT INTO workflows VALUES (65, 3, 1, 6, 3);
INSERT INTO workflows VALUES (66, 3, 2, 1, 3);
INSERT INTO workflows VALUES (67, 3, 2, 3, 3);
INSERT INTO workflows VALUES (68, 3, 2, 4, 3);
INSERT INTO workflows VALUES (69, 3, 2, 5, 3);
INSERT INTO workflows VALUES (70, 3, 2, 6, 3);
INSERT INTO workflows VALUES (71, 3, 3, 1, 3);
INSERT INTO workflows VALUES (72, 3, 3, 2, 3);
INSERT INTO workflows VALUES (73, 3, 3, 4, 3);
INSERT INTO workflows VALUES (74, 3, 3, 5, 3);
INSERT INTO workflows VALUES (75, 3, 3, 6, 3);
INSERT INTO workflows VALUES (76, 3, 4, 1, 3);
INSERT INTO workflows VALUES (77, 3, 4, 2, 3);
INSERT INTO workflows VALUES (78, 3, 4, 3, 3);
INSERT INTO workflows VALUES (79, 3, 4, 5, 3);
INSERT INTO workflows VALUES (80, 3, 4, 6, 3);
INSERT INTO workflows VALUES (81, 3, 5, 1, 3);
INSERT INTO workflows VALUES (82, 3, 5, 2, 3);
INSERT INTO workflows VALUES (83, 3, 5, 3, 3);
INSERT INTO workflows VALUES (84, 3, 5, 4, 3);
INSERT INTO workflows VALUES (85, 3, 5, 6, 3);
INSERT INTO workflows VALUES (86, 3, 6, 1, 3);
INSERT INTO workflows VALUES (87, 3, 6, 2, 3);
INSERT INTO workflows VALUES (88, 3, 6, 3, 3);
INSERT INTO workflows VALUES (89, 3, 6, 4, 3);
INSERT INTO workflows VALUES (90, 3, 6, 5, 3);
INSERT INTO workflows VALUES (91, 1, 1, 2, 4);
INSERT INTO workflows VALUES (92, 1, 1, 3, 4);
INSERT INTO workflows VALUES (93, 1, 1, 4, 4);
INSERT INTO workflows VALUES (94, 1, 1, 5, 4);
INSERT INTO workflows VALUES (95, 1, 2, 3, 4);
INSERT INTO workflows VALUES (96, 1, 2, 4, 4);
INSERT INTO workflows VALUES (97, 1, 2, 5, 4);
INSERT INTO workflows VALUES (98, 1, 3, 2, 4);
INSERT INTO workflows VALUES (99, 1, 3, 4, 4);
INSERT INTO workflows VALUES (100, 1, 3, 5, 4);
INSERT INTO workflows VALUES (101, 1, 4, 2, 4);
INSERT INTO workflows VALUES (102, 1, 4, 3, 4);
INSERT INTO workflows VALUES (103, 1, 4, 5, 4);
INSERT INTO workflows VALUES (104, 2, 1, 2, 4);
INSERT INTO workflows VALUES (105, 2, 1, 3, 4);
INSERT INTO workflows VALUES (106, 2, 1, 4, 4);
INSERT INTO workflows VALUES (107, 2, 1, 5, 4);
INSERT INTO workflows VALUES (108, 2, 2, 3, 4);
INSERT INTO workflows VALUES (109, 2, 2, 4, 4);
INSERT INTO workflows VALUES (110, 2, 2, 5, 4);
INSERT INTO workflows VALUES (111, 2, 3, 2, 4);
INSERT INTO workflows VALUES (112, 2, 3, 4, 4);
INSERT INTO workflows VALUES (113, 2, 3, 5, 4);
INSERT INTO workflows VALUES (114, 2, 4, 2, 4);
INSERT INTO workflows VALUES (115, 2, 4, 3, 4);
INSERT INTO workflows VALUES (116, 2, 4, 5, 4);
INSERT INTO workflows VALUES (117, 3, 1, 2, 4);
INSERT INTO workflows VALUES (118, 3, 1, 3, 4);
INSERT INTO workflows VALUES (119, 3, 1, 4, 4);
INSERT INTO workflows VALUES (120, 3, 1, 5, 4);
INSERT INTO workflows VALUES (121, 3, 2, 3, 4);
INSERT INTO workflows VALUES (122, 3, 2, 4, 4);
INSERT INTO workflows VALUES (123, 3, 2, 5, 4);
INSERT INTO workflows VALUES (124, 3, 3, 2, 4);
INSERT INTO workflows VALUES (125, 3, 3, 4, 4);
INSERT INTO workflows VALUES (126, 3, 3, 5, 4);
INSERT INTO workflows VALUES (127, 3, 4, 2, 4);
INSERT INTO workflows VALUES (128, 3, 4, 3, 4);
INSERT INTO workflows VALUES (129, 3, 4, 5, 4);
INSERT INTO workflows VALUES (130, 1, 1, 5, 5);
INSERT INTO workflows VALUES (131, 1, 2, 5, 5);
INSERT INTO workflows VALUES (132, 1, 3, 5, 5);
INSERT INTO workflows VALUES (133, 1, 4, 5, 5);
INSERT INTO workflows VALUES (134, 1, 3, 4, 5);
INSERT INTO workflows VALUES (135, 2, 1, 5, 5);
INSERT INTO workflows VALUES (136, 2, 2, 5, 5);
INSERT INTO workflows VALUES (137, 2, 3, 5, 5);
INSERT INTO workflows VALUES (138, 2, 4, 5, 5);
INSERT INTO workflows VALUES (139, 2, 3, 4, 5);
INSERT INTO workflows VALUES (140, 3, 1, 5, 5);
INSERT INTO workflows VALUES (141, 3, 2, 5, 5);
INSERT INTO workflows VALUES (142, 3, 3, 5, 5);
INSERT INTO workflows VALUES (143, 3, 4, 5, 5);
INSERT INTO workflows VALUES (144, 3, 3, 4, 5);


ALTER TABLE ONLY attachments
    ADD CONSTRAINT attachments_pkey PRIMARY KEY (id);

ALTER TABLE ONLY auth_sources
    ADD CONSTRAINT auth_sources_pkey PRIMARY KEY (id);


ALTER TABLE ONLY boards
    ADD CONSTRAINT boards_pkey PRIMARY KEY (id);

ALTER TABLE ONLY changes
    ADD CONSTRAINT changes_pkey PRIMARY KEY (id);

ALTER TABLE ONLY changesets
    ADD CONSTRAINT changesets_pkey PRIMARY KEY (id);

ALTER TABLE ONLY comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);

ALTER TABLE ONLY custom_fields
    ADD CONSTRAINT custom_fields_pkey PRIMARY KEY (id);

ALTER TABLE ONLY custom_values
    ADD CONSTRAINT custom_values_pkey PRIMARY KEY (id);

ALTER TABLE ONLY documents
    ADD CONSTRAINT documents_pkey PRIMARY KEY (id);

ALTER TABLE ONLY enabled_modules
    ADD CONSTRAINT enabled_modules_pkey PRIMARY KEY (id);

ALTER TABLE ONLY enumerations
    ADD CONSTRAINT enumerations_pkey PRIMARY KEY (id);

ALTER TABLE ONLY issue_categories
    ADD CONSTRAINT issue_categories_pkey PRIMARY KEY (id);

ALTER TABLE ONLY issue_relations
    ADD CONSTRAINT issue_relations_pkey PRIMARY KEY (id);

ALTER TABLE ONLY issue_statuses
    ADD CONSTRAINT issue_statuses_pkey PRIMARY KEY (id);

ALTER TABLE ONLY issues
    ADD CONSTRAINT issues_pkey PRIMARY KEY (id);

ALTER TABLE ONLY journal_details
    ADD CONSTRAINT journal_details_pkey PRIMARY KEY (id);

ALTER TABLE ONLY journals
    ADD CONSTRAINT journals_pkey PRIMARY KEY (id);

ALTER TABLE ONLY members
    ADD CONSTRAINT members_pkey PRIMARY KEY (id);

ALTER TABLE ONLY news
    ADD CONSTRAINT news_pkey PRIMARY KEY (id);

ALTER TABLE ONLY projects
    ADD CONSTRAINT projects_pkey PRIMARY KEY (id);

ALTER TABLE ONLY queries
    ADD CONSTRAINT queries_pkey PRIMARY KEY (id);

ALTER TABLE ONLY repositories
    ADD CONSTRAINT repositories_pkey PRIMARY KEY (id);

ALTER TABLE ONLY roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);

ALTER TABLE ONLY schema_migrations
    ADD CONSTRAINT schema_migrations_pkey PRIMARY KEY (version);

ALTER TABLE ONLY settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);

ALTER TABLE ONLY time_entries
    ADD CONSTRAINT time_entries_pkey PRIMARY KEY (id);

ALTER TABLE ONLY tokens
    ADD CONSTRAINT tokens_pkey PRIMARY KEY (id);

ALTER TABLE ONLY trackers
    ADD CONSTRAINT trackers_pkey PRIMARY KEY (id);

ALTER TABLE ONLY user_preferences
    ADD CONSTRAINT user_preferences_pkey PRIMARY KEY (id);

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);

ALTER TABLE ONLY versions
    ADD CONSTRAINT versions_pkey PRIMARY KEY (id);

ALTER TABLE ONLY watchers
    ADD CONSTRAINT watchers_pkey PRIMARY KEY (id);

ALTER TABLE ONLY wiki_content_versions
    ADD CONSTRAINT wiki_content_versions_pkey PRIMARY KEY (id);

ALTER TABLE ONLY wiki_contents
    ADD CONSTRAINT wiki_contents_pkey PRIMARY KEY (id);

ALTER TABLE ONLY wiki_pages
    ADD CONSTRAINT wiki_pages_pkey PRIMARY KEY (id);

ALTER TABLE ONLY wiki_redirects
    ADD CONSTRAINT wiki_redirects_pkey PRIMARY KEY (id);

ALTER TABLE ONLY wikis
    ADD CONSTRAINT wikis_pkey PRIMARY KEY (id);

ALTER TABLE ONLY workflows
    ADD CONSTRAINT workflows_pkey PRIMARY KEY (id);

CREATE INDEX boards_project_id ON boards USING btree (project_id);
CREATE INDEX changesets_changeset_id ON changes USING btree (changeset_id);
CREATE UNIQUE INDEX changesets_issues_ids ON changesets_issues USING btree (changeset_id, issue_id);
CREATE UNIQUE INDEX changesets_repos_rev ON changesets USING btree (repository_id, revision);
CREATE INDEX custom_values_customized ON custom_values USING btree (customized_type, customized_id);
CREATE INDEX documents_project_id ON documents USING btree (project_id);
CREATE INDEX enabled_modules_project_id ON enabled_modules USING btree (project_id);
CREATE INDEX issue_categories_project_id ON issue_categories USING btree (project_id);
CREATE INDEX issues_project_id ON issues USING btree (project_id);
CREATE INDEX journal_details_journal_id ON journal_details USING btree (journal_id);
CREATE INDEX journals_journalized_id ON journals USING btree (journalized_id, journalized_type);
CREATE INDEX news_project_id ON news USING btree (project_id);
CREATE INDEX projects_trackers_project_id ON projects_trackers USING btree (project_id);
CREATE INDEX time_entries_issue_id ON time_entries USING btree (issue_id);
CREATE INDEX time_entries_project_id ON time_entries USING btree (project_id);
CREATE INDEX versions_project_id ON versions USING btree (project_id);
CREATE INDEX wiki_content_versions_wcid ON wiki_content_versions USING btree (wiki_content_id);
CREATE INDEX wiki_contents_page_id ON wiki_contents USING btree (page_id);
CREATE INDEX wiki_pages_wiki_id_title ON wiki_pages USING btree (wiki_id, title);
CREATE INDEX wiki_redirects_wiki_id_title ON wiki_redirects USING btree (wiki_id, title);
CREATE INDEX wikis_project_id ON wikis USING btree (project_id);
CREATE INDEX wkfs_role_tracker_old_status ON workflows USING btree (role_id, tracker_id, old_status_id);
