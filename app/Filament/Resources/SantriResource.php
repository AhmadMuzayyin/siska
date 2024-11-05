<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Santri;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SantriResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SantriResource\RelationManagers;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'phosphor-users';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Data Santri';
    public static ?int $navigationGroupSort = 1;
    public static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Santri')
                    ->columns(2)
                    ->schema([
                        TextInput::make('noinduk')
                            ->required()
                            ->maxLength(255)
                            ->numeric()
                            ->label('No. Induk')
                            ->minValue(1),
                        Select::make('kelas_id')
                            ->required()
                            ->relationship('kelas', 'nama')
                            ->label('Kelas'),
                        TextInput::make('nama_lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nama_panggilan')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tempat_lahir')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('tanggal_lahir')
                            ->required(),
                        TextInput::make('anak_ke')
                            ->required()
                            ->numeric()
                            ->minValue(1),
                        Select::make('jenis_kelamin')
                            ->required()
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ]),
                        Textarea::make('alamat')
                            ->required()
                            ->maxLength(255)
                            ->rows(3),
                    ]),
                Section::make('Data Orang Tua')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_ayah')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Ayah'),
                        TextInput::make('pendidikan_ayah')
                            ->required()
                            ->maxLength(255)
                            ->label('Pendidikan Ayah'),
                        TextInput::make('pekerjaan_ayah')
                            ->required()
                            ->maxLength(255)
                            ->label('Pekerjaan Ayah'),
                        TextInput::make('telepon_ayah')
                            ->required()
                            ->maxLength(255)
                            ->label('Telepon Ayah'),
                        TextInput::make('nama_ibu')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Ibu'),
                        TextInput::make('pendidikan_ibu')
                            ->required()
                            ->maxLength(255)
                            ->label('Pendidikan Ibu'),
                        TextInput::make('pekerjaan_ibu')
                            ->required()
                            ->maxLength(255)
                            ->label('Pekerjaan Ibu'),
                        TextInput::make('telepon_ibu')
                            ->required()
                            ->maxLength(255)
                            ->label('Telepon Ibu'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('noinduk')
                    ->searchable(),
                TextColumn::make('nama_lengkap')
                    ->searchable(),
                TextColumn::make('nama_panggilan')
                    ->searchable(),
                TextColumn::make('kelas.nama')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tanggal Dibuat'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tanggal Diubah'),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->relationship('kelas', 'nama')
                    ->label('Kelas'),
                SelectFilter::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->label('Jenis Kelamin'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Detail Santri')
                    ->modalCancelActionLabel('Tutup'),
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->modalHeading('Edit Data Santri')
                    ->modalSubmitActionLabel('Perbarui')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data Santri')
                    ->modalDescription('Apakah anda yakin ingin menghapus data ini?')
                    ->modalCancelActionLabel('Batal')
                    ->modalSubmitActionLabel('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSantris::route('/'),
            'create' => Pages\CreateSantri::route('/create'),
            'edit' => Pages\EditSantri::route('/{record}/edit'),
        ];
    }
}
