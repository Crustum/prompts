# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0]

### Added

- CakePHP Console adapter over Laravel Prompts (`crustum/prompts`) with helpers under the `Crustum/Prompts.*` namespace
- Interactive input helpers: Text, Textarea, Number, Password, Confirm, Select, MultiSelect, Suggest, Search, MultiSearch, AutoComplete, and Pause
- Display helpers: Note with typed shortcuts (Error, Warning, Alert, Info, Intro, Outro), Callout, Table, Grid, Title, Clear, and Notify
- Advanced UI helpers: Form (`FormBuilder`), DataTable, Progress, Spin, Task, and Stream
- `run(array $args): mixed` return API on helpers (with Cake `output()` also supported) and automatic `ConsoleIoFallbacks::setIo()` binding
- Support for plain arrays or `Cake\Collection\Collection` as option lists
- Input transforms before validation and multi-step Forms for collecting structured answers
- Progress bars, spinners, tasks with live log output, and stream helpers for long-running console work
- Terminal title and clear helpers for console UX
- `ConsoleIoFallbacks` with default registration on plugin bootstrap for non-TTY / native Windows environments
- Environment-aware fallbacks (`enableEnvironmentFallbacks`) and `PromptState::reset()` for tests
- Plugin load via `config/plugins.php` or `Application::bootstrap()` with documented direct `Laravel\Prompts\*` usage when needed
- Comprehensive documentation covering installation, every helper, transforms, forms, messages, tables, progress, tasks, streams, terminal considerations, and fallbacks
